<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Deposits;
use App\Models\User;
use Exception;

class GPOWebhook extends Controller
{
    public function appypayWebhook(Request $request)
    {
        try {
            $payload = $request->json()->all();

            $merchantTransactionId = $payload['merchantTransactionId'] ?? null;
            if (!$merchantTransactionId) {
                return response()->json(['success' => false, 'message' => 'merchantTransactionId ausente'], 400);
            }

            $isSuccessful = ($payload['responseStatus']['successful'] ?? false) === true
                         || ($payload['responseStatus']['status'] ?? '') === 'Success';

            if ($isSuccessful) {
                DB::beginTransaction();
                try {
                    // 🔒 LOCK para evitar race condition + verificar status actual
                    $deposit = Deposits::where('txn_id', $merchantTransactionId)
                        ->lockForUpdate()
                        ->first();

                    if (!$deposit) {
                        DB::rollBack();
                        Log::warning('Depósito não encontrado', ['txn_id' => $merchantTransactionId]);
                        return response()->json(['success' => false, 'message' => 'Depósito não encontrado'], 404);
                    }

                    if ($deposit->status === 'active') {
                        DB::rollBack();
                        Log::info('Depósito já estava activo, ignorado', ['txn_id' => $merchantTransactionId]);
                        return response()->json(['success' => true, 'message' => 'Já processado']);
                    }

                    $updated = DB::table('deposits')
                        ->where('id', $deposit->id)
                        ->where('status', '!=', 'active') 
                        ->update(['status' => 'active']);
                   
                    if ($updated > 0) {
                        User::findOrFail($deposit->user_id)
                            ->increment('wallet', $deposit->amount);
                    } else {
                        Log::warning('Nenhuma linha actualizada no depósito', [
                            'txn_id'     => $merchantTransactionId,
                            'deposit_id' => $deposit->id,
                            'status_actual' => $deposit->status,
                        ]);
                    }

                    DB::commit();
                    return response()->json(['success' => true, 'message' => 'Pagamento processado']);

                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error('Erro no webhook ao atualizar depósito', [
                        'txn_id' => $merchantTransactionId,
                        'error'  => $e->getMessage(),
                        'trace'  => $e->getTraceAsString(),
                    ]);
                    return response()->json(['success' => false], 500);
                }

            } else {
                $deposit = Deposits::where('txn_id', $merchantTransactionId)->first();
                if ($deposit && $deposit->status !== 'active') {
                    DB::table('deposits')
                        ->where('id', $deposit->id)
                        ->update(['status' => 'failed']);
                }
               
                return response()->json(['success' => false, 'message' => 'Pagamento não bem-sucedido'], 200);
            }

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro interno'], 500);
        }
    }
}