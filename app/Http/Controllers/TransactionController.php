<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\CheckBalancePayer;
use App\Rules\PayerUserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\TransactionException;
use App\Exceptions\MockTransactionException;
use App\Models\Wallet;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function outcome(Request $request)
    {
        $this->validate($request, [
            'value' => [
                'required',
                'numeric',
                new CheckBalancePayer($request->all())
            ],
            'payee_wallet_id' => 'required|max:255|exists:wallets,id',
            'payer_wallet_id' => [
                'required',
                'max:255',
                'exists:wallets,id',
                new PayerUserType()
            ]
        ]);

        DB::beginTransaction();

        try {
            $transaction = new Transaction();
            $transaction->value = $request->input('value');
            $transaction->payer_wallet_id = $request->input('payer_wallet_id');
            $transaction->payee_wallet_id = $request->input('payee_wallet_id');
            $transaction->save();

            $wallet_payeer = Wallet::find($request->input('payer_wallet_id'));

            if ($transaction->value > $wallet_payeer->balance) {
                throw new TransactionException('Insufficient balance to complete the transaction!');
            }

            if ($transaction->value <= $wallet_payeer->balance) {
                $wallet_payeer->balance -= $transaction->value;
                $wallet_payeer->save();
            }

            $wallet_payee = Wallet::find($request->input('payee_wallet_id'));
            $wallet_payee->balance += $transaction->value;
            $wallet_payee->save();

            DB::commit();
            return response()->json(['message' => 'The transaction was completed successfully!'], 200);
        } catch (TransactionException $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (MockTransactionException $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 503);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'The transaction failed, please try again later!'], 500);
        }
    }
}
