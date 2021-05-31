<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Events\SendTransactionSuccessfullyNotification;
use App\Providers\MockRequestServiceProvider;
use App\Exceptions\Transaction\TransactionException;
use App\Exceptions\MockService\MockRequestException;
use App\Exceptions\Transaction\TransactionRepositoryException;

class TransactionRepository
{
    private $mock_provider;
    private $data;

    public function __construct()
    {
        $this->mock_provider = new MockRequestServiceProvider();
    }

    public function makeTransaction(object $data): Transaction
    {
        try {
            $this->data = (object) $data;
            $mock_authorized_transaction = $this->checkMockTransactionAvailable();
            $balance_avaible = $this->checkPayerHasBalance();

            if (!$mock_authorized_transaction) {
                throw new MockRequestException('Service unavailable.');
            }

            if (!$balance_avaible) {
                throw new TransactionException('Insufficient balance to complete the transaction!');
            }

            return $this->saveTransaction();
        } catch (TransactionException $exception) {
            throw new TransactionRepositoryException($exception->getMessage(), 422);
        } catch (MockRequestException $exception) {
            throw new TransactionRepositoryException($exception->getMessage(), 503);
        }
    }

    public function checkMockTransactionAvailable(): bool
    {
        return $this->mock_provider->enableOperation(
            config('MockTransaction.method'),
            config('MockTransaction.url')
        );
    }

    public function checkPayerHasBalance(): bool
    {
        $wallet_payer = Wallet::find($this->data->payer_wallet_id);

        if ($this->data->value > $wallet_payer->balance) {
            return false;
        }

        return true;
    }

    public function saveTransaction(): Transaction
    {
        return DB::transaction(function () {
            $transaction = new Transaction();
            $transaction->value = $this->data->value;
            $transaction->payer_wallet_id = $this->data->payer_wallet_id;
            $transaction->payee_wallet_id = $this->data->payee_wallet_id;
            $transaction->save();

            $transaction->payer->debit($this->data->value);
            $transaction->payee->credit($this->data->value);

            event(new SendTransactionSuccessfullyNotification($transaction));

            return $transaction->unsetRelation('payer')->unsetRelation('payee');
        });
    }
}
