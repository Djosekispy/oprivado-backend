<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Deposits;
use App\Models\PaymentGateways;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GPOController extends Controller
{
    public function processTransaction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => ['required', 'numeric', 'min:0.01'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "error" => "Valor inválido.",
                    "debug" => config('app.debug') ? $validator->errors()->all() : null
                ]);
            }

            $amount = $request->amount;
            $merchantTransactionId = time();
            $payment = PaymentGateways::whereName('gpo')->firstOrFail();
            $fee = $payment->fee;
            $cents = $payment->fee_cents;
            $taxes = config('settings.tax_on_wallet') ? ($amount * auth()->user()->isTaxable()->sum('percentage') / 100) : 0;
            $amount = number_format($amount + ($amount * $fee / 100) + $cents + $taxes, 2, '.', '');

            $percentageApplied = $cents == 0.00 ?
                (($fee != 0.0) ? $fee . '%' : null)
                : (($fee != 0.0) ? $fee . '% + ' : null) . $cents;

            $transactionFeeAmount = number_format($amount + ($amount * $fee / 100) + $cents, 2, '.', '');
            $transactionFee = ($transactionFeeAmount - $amount);
			
			$deposit = new Deposits();
            $deposit->user_id = auth()->id();
            $deposit->txn_id = $merchantTransactionId;
            $deposit->amount = $amount;
            $deposit->payment_gateway = 'gpo';
            $deposit->status = 'pending';
            $deposit->percentage_applied = $percentageApplied;
            $deposit->transaction_fee = $transactionFee;
            $deposit->taxes = auth()->user()->taxesPayable();
            $deposit->save();

            return response()->json([
                "success" => true,
                "message" => "Transação criada com sucesso.",
                "transaction" => [
                    'id' => $deposit->id,
                    'taxes_ids' => $merchantTransactionId,
                    'amount' => $amount
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao processar a transação.",
                "debug" => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function appypayInit(Request $request)
    {
        try {
            $newTransaction = $this->processTransaction($request);
            $transactionData = $newTransaction->getData();

            if ($transactionData->success) {
                $amount = $transactionData->transaction->amount;
                $merchantTransactionId = $transactionData->transaction->taxes_ids;
                $phone = $request->phone;

                return response()->json([
                    "success" => true,
                    "merchantTransactionId" => $merchantTransactionId,
                    "amount" => $amount,
                    "phone" => $phone,
                ]);
            }
            return response()->json([
                "success" => false,
                "error" => "Erro ao inicializar a transação.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao processar o pagamento.",
                "debug" => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

    public function appypayFinish(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'merchantTransactionId' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }
        $merchantTransactionId = $request->merchantTransactionId;
        $phone = $request->phone;
        $amountToPay = (float) $request->amount;

        $deposit = Deposits::where('txn_id', $merchantTransactionId)->first();

        if (!$deposit) {
            return response()->json([
                'success' => false,
                'error' => 'Transação não encontrada.',
            ]);
        }

        $token = $this->getAuthToken();
        $acessToken = $token['access_token'];
		if (!isset($token['access_token'])) {
			return response()->json([
				'success' => false,
				'error' => 'Falha ao obter token de acesso.',
			]);
		}
        $chargeResponse = $this->createCharge($acessToken, $merchantTransactionId, $amountToPay, $phone);
        if (!$chargeResponse) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao criar a cobrança.',
            ]);
        }

        if ($chargeResponse->responseStatus->successful) {
                return response()->json([
                    'success' => true,
                ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $chargeResponse->responseStatus->message ?? 'Erro desconhecido.',
            ]);
        }
    }

    private function getAuthToken()
    {
               $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://login.microsoftonline.com/auth.appypay.co.ao/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  [
                'grant_type' => 'client_credentials',
                'client_id' => env('APPYPAY_CLIENT_ID', '48fdb463-035a-40a0-be28-98f362b64f54'),
                'client_secret' => env('APPYPAY_CLIENT_SECRET'),
                'resource' => env('APPYPAY_RESOURCE', 'bee57785-7a19-4f1c-9c8d-aa03f2f0e333')
            ],
            CURLOPT_HTTPHEADER => array(
                "Accept-Language: pt-BR",
                'Cookie: esctx=PAQABAAEAAAAtyolDObpQQ5VtlI4uGjEPJH5Nnjbs2RDvmOr5FccPFV4JeUy-r44mvoO_vlmlY2Uy3E347ZZFaxYb84oIf0vhqz1KaByGebYMniCbnToGv_luK_U3DVAal7Zu2LrQNVPplJIBUtMi1niqRz_HNyDrz4JcURvIFf3_4tQW4JJnLNMgomXw77WQyOm2rmO0nz0gAA; fpc=AvIEwPLobVtArlqpbdUSjhFON_WHBAAAAMRaUtwOAAAA; stsservicecookie=estsfd; x-ms-gateway-slice=estsfd'
            ),
        ));
        
    
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($response != null) {
            return json_decode($response, true);
        }
    
        return json_decode($error, true);
    }

    private function createCharge($token, $merchantTransactionId, $amountToPay, $phone)
    {
 		$curl = curl_init();
   		 curl_setopt_array($curl, [
        CURLOPT_URL => "https://gwy-api.appypay.co.ao/v2.0/charges",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 90,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n  \"currency\": \"AOA\",\n  \"amount\": " . $amountToPay . ",\n  \"paymentMethod\": \"GPO_8c234e59-03a1-4c2c-8ca6-0d834f1c1e2f\",\n  \"description\": \"Carregamento de carteira Oprivado\",\n  \"merchantTransactionId\": \"" . $merchantTransactionId . "\",\n  \"paymentInfo\": {\n    \"phoneNumber\": \"" . $phone . "\"\n  }\n}",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Accept-Language: pt-BR",
            "Authorization: Bearer " . $token,
            "Content-Type: application/json"
        ],
    ]);

   		 $response = curl_exec($curl);
    		curl_close($curl);
   		 $resp = json_decode($response);
        return $resp ? json_decode($response) : null ;
    }
}