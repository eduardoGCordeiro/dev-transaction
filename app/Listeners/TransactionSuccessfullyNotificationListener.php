<?php

namespace App\Listeners;

use App\Events\SendTransactionSuccessfullyNotification;
use Illuminate\Support\Facades\Log;
use App\Exceptions\MockService\MockRequestException;
use App\Providers\MockRequestServiceProvider;

class TransactionSuccessfullyNotificationListener
{
    private $mock_provider;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mock_provider = new MockRequestServiceProvider();
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendTransactionSuccessfullyNotification  $event
     * @return void
     */
    public function handle(SendTransactionSuccessfullyNotification $event)
    {
        try {
            $this->mock_provider->enableOperation(
                config('MockEmail.method'),
                config('MockEmail.url')
            );
        } catch (MockRequestException $exception) {
            Log::critical('[Transaction notification error]', [
                'message' => $exception->getMessage(),
                'transaction' => $event->transaction
            ]);
        }
    }
}
