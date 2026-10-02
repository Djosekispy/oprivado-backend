<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Deposits;
use App\Models\User;
use Exception;

class ReferenceWebhook extends Controller
{
    /**
     * Manipula o webhook de pagamento de referência.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        try {
            $payload = $request->json()->all();

            // Validação básica
            if (!isset($payload['merchantTransactionId'], $payload['amount'], $payload['responseStatus']['status'])) {
              
                return response()->json(['success' => false, 'message' => 'Dados incompletos'], 400);
            }

            $merchantTransactionId = $payload['merchantTransactionId'];
            $amount = $payload['amount'];
            $status = $payload['responseStatus']['status'];

            $deposit = Deposits::where('txn_id', $merchantTransactionId)->first();
            if (!$deposit) {
              
                return response()->json(['success' => false, 'message' => 'Depósito não encontrado'], 404);
            }

            if ($status === 'Success') {
                DB::beginTransaction();
                try {
                    $deposit->status = 'active';
                    $deposit->save();
                    User::find($deposit->user_id)->increment('wallet', $amount);
                    DB::commit();
                 
                    return response()->json(['success' => true, 'message' => 'Pagamento processado com sucesso']);
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error('Erro ao atualizar depósito', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'merchantTransactionId' => $merchantTransactionId
                    ]);
                    return response()->json(['success' => false, 'message' => 'Erro ao processar pagamento'], 500);
                }
            } else {
                Log::info('Pagamento não foi bem-sucedido', ['merchantTransactionId' => $merchantTransactionId, 'status' => $status]);
                return response()->json(['success' => false, 'message' => 'Pagamento não foi bem-sucedido'], 200);
            }
        } catch (Exception $e) {
            Log::error('Erro no webhook de referência', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Erro interno'], 500);
        }
    }
}
