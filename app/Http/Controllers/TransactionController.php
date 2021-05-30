<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\CheckBalancePayer;
use App\Rules\PayerUserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\TransactionException;
use App\Exceptions\MockRequestException;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Providers\MockRequestServiceProvider;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    private $mock_provider;

    public function __construct()
    {
        $this->mock_provider = new MockRequestServiceProvider();
    }

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
            $transaction_authorized = $this->mock_provider->enableOperation(
                config('MockTransaction.method'),
                config('MockTransaction.url')
            );

            if ($transaction_authorized) {
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
            }

            DB::commit();
            return response()->json(['message' => 'The transaction was completed successfully!'], 200);
        } catch (TransactionException $exception) {
            DB::rollback();
            return response()->json(['message' => $exception->getMessage()], 422);
        } catch (MockRequestException $exception) {
            DB::rollback();
            return response()->json(['message' => $exception->getMessage()], 503);
        } catch (\Exception $exception) {
            DB::rollback();

            Log::critical('[Transaction error]', [
                'message' => $exception->getMessage()
            ]);

            return response()->json(['message' => 'The transaction failed, please try again later!'], 500);
        }
    }
}
