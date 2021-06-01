<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Rules\PayerUserType;
use App\Rules\CheckBalancePayer;
use App\Repositories\TransactionRepository;
use App\Exceptions\Transaction\TransactionRepositoryException;

class TransactionController extends Controller
{
    private $repository;

    /**
    * @var TransactionRepository
    */
    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createTransaction(Request $request)
    {
        $this->validate($request, [
            'value' => [
                'required',
                'numeric',
                'min: 0.01',
                new CheckBalancePayer($request->all())
            ],
            'payee_wallet_id' => 'required|max:255|exists:wallets,id|different:payer_wallet_id',
            'payer_wallet_id' => [
                'required',
                'max:255',
                'exists:wallets,id',
                new PayerUserType()
            ]
        ]);

        try {
            $payload = (object) $request->only(['value', 'payee_wallet_id', 'payer_wallet_id']);
            $transaction = $this->repository->makeTransaction($payload);

            return response()->json([
                'transaction' => $transaction,
                'message' => 'The transaction was completed successfully!'
            ], 200);
        } catch (TransactionRepositoryException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        } catch (\Exception $exception) {
            Log::critical('[Transaction error]', [
                'message' => $exception->getMessage()
            ]);

            return response()->json(['message' => 'The transaction failed, please try again later!'], 500);
        }
    }
}
