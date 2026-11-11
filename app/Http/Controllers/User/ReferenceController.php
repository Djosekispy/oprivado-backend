<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Deposits;
use App\Models\PaymentGateways;
use App\Models\User;
use Exception;
use Carbon\Carbon;

class ReferenceController extends Controller
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

            $payment = PaymentGateways::whereName('Multicaixa')->firstOrFail();
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
            $deposit->payment_gateway = 'Multicaixa';
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
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "error" => "Erro ao processar a transação.",
                "debug" => config('app.debug') ? $e->getMessage() : null
            ]);
        }
    }

 public function mcxInit(Request $request)
{
    try {
        $newTransaction = $this->processTransaction($request);
        $transactionData = $newTransaction->getData(); 
        
        if (!$transactionData->success) {
            return $newTransaction; 
        }

        $referenceNumber = (string) random_int(100000000, 999999999);
        $dueDate = date("Y-m-d\TH:i:s", strtotime("+1 day"));
        $userId = auth()->id();
        $userName = auth()->user()->name;
        $amount = $transactionData->transaction->amount;
        $merchantTransactionId = $transactionData->transaction->taxes_ids;

        $token = $this->getAuthToken();
        if (!$token || !isset($token['access_token'])) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao obter o token de autenticação.',
            ], 500);
        }

        $response = $this->generateAppyPayPaymentReference([
            'accessToken' => $token['access_token'],
            'userId' => $userId,
            'amount' => $amount,
            'merchantTransactionId' => $merchantTransactionId,
            "referenceNumber" => $referenceNumber,
            "dueDate" => $dueDate
        ]);

        if (!is_array($response) || !isset($response['body'])) {
            return response()->json([
                "success" => false,
                "error" => "Resposta inválida da API da AppyPay",
                "raw_response" => $response
            ], 500);
        }

        $responseBody = $response['body'];
        
        if (!isset($responseBody['responseStatus']['successful']) || !$responseBody['responseStatus']['successful']) {
            $errorMessage = $responseBody['responseStatus']['message'] ?? 'Erro desconhecido ao gerar referência';
            return response()->json([
                "success" => false,
                "error" => $errorMessage,
                "raw_response" => $responseBody
            ], 400);
        }

        $referenceData = $responseBody['responseStatus']['reference'] ?? null;
        
        if (!$referenceData) {
            return response()->json([
                "success" => false,
                "error" => "Dados da referência não encontrados na resposta",
                "raw_response" => $responseBody
            ], 500);
        }

        return response()->json([
            "success" => true,
            "amount" => $amount,
            "merchantTransactionId" => $merchantTransactionId,
            "message" => 'Referência gerada com sucesso',
            "referenceNumber" => $referenceData['referenceNumber'] ?? $referenceNumber,
            "dueDate" => $referenceData['dueDate'] ?? $dueDate,
            "entity" => $referenceData['entity'] ?? '10007',
        ]);

    } catch (Exception $e) {
        return response()->json([
            "success" => false,
            "error" => "Erro ao processar o pagamento: " . $e->getMessage(),
            "debug" => config('app.debug') ? $e->getTrace() : null
        ], 500);
    }
}

    private function getAuthToken()
    {
        $clientId = env('APPYPAY_CLIENT_ID');
        $clientSecret = env('APPYPAY_CLIENT_SECRET');
        $resource = env('APPYPAY_RESOURCE');

        if (!$clientId || !$clientSecret || !$resource) {
            return ['error' => 'Credenciais do provedor não configuradas.'];
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => env('APPYPAY_TOKEN_URL', 'https://login.microsoftonline.com/auth.appypay.co.ao/oauth2/token'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'resource' => $resource,
            ],
            CURLOPT_HTTPHEADER => [
                "Accept-Language: pt-BR",
            ],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response !== false && $response !== null && $response !== '') {
            return json_decode($response, true);
        }

        return json_decode($error, true);
    }

    private function generateAppyPayPaymentReference($data)
    {
        $curl = curl_init();

        $requiredFields = ['userId', 'amount', 'merchantTransactionId', 'referenceNumber', 'dueDate', 'accessToken'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new InvalidArgumentException("O campo '$field' é obrigatório");
            }
        }

        $callbackParams = [
            'userId' => $data['userId'],
            'amount' => $data['amount'],
            'merchantTransactionId' => $data['merchantTransactionId'],
            'referenceNumber' => $data['referenceNumber'],
        ];

        $paymentMethod = env('APPYPAY_REFERENCE_PAYMENT_METHOD_ID');
        $entity = env('APPYPAY_REFERENCE_ENTITY', '10007');
        $apiUrl = env('APPYPAY_API_URL', 'https://gwy-api.appypay.co.ao/v2.0');

        if (!$paymentMethod) {
            throw new InvalidArgumentException('APPYPAY_REFERENCE_PAYMENT_METHOD_ID não configurado.');
        }

        $payload = [
            "amount" => (float) $data['amount'],
            "currency" => "AOA",
            "paymentMethod" => $paymentMethod,
            "description" => "Carregar carteira OPrivado",
            "merchantTransactionId" => (string) $data['merchantTransactionId'],
            "dueDate" => $data['dueDate'],
            "paymentInfo" => [
                "referenceNumber" => (string) $data['referenceNumber'],
                "dueDate" => $data['dueDate']
            ],
            "references" => [
                [
                    "referenceNumber" => (string) $data['referenceNumber'],
                    "currency" => "AOA",
                    "dueDate" => $data['dueDate'],
                    "amounts" => [
                        [
                            "amount" => (float) $data['amount'],
                            "descriptionLine1" => "Carregar carteira OPrivado",
                            "descriptionLine2" => "Ref: " . $data['merchantTransactionId']
                        ]
                    ],
                    "minAmount" => (float) $data['amount'],
                    "maxAmount" => (float) $data['amount'],
                    "startDate" => Carbon::now()->toIso8601String(),
                    "expirationDate" => $data['dueDate'],
                    "entity" => $entity
                ]
            ]
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl . '/charges',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Accept-Language: pt-BR",
                "Content-Type: application/json",
                'Authorization: Bearer ' . $data['accessToken']
            ],
            CURLOPT_HEADER => true
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($error) {
            return ['error' => true, 'message' => $error];
        }

        $parts = explode("\r\n\r\n", $response, 2);
        $body = isset($parts[1]) ? json_decode($parts[1], true) : null;

        return [
            'status_code' => $httpCode,
            'body' => $body,
            'raw_response' => $response
        ];
    }
}