<?php

use App\Models\Application;
use App\Models\CorporateUser;
use App\Models\PersonUser;

class TransactionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testeWrongValueInTransaction()
    {
        $application = Application::factory()->create();
        $payer = PersonUser::factory()->create();
        $payee = CorporateUser::factory()->create();

        $payload = [
            'value' => '100',
            'payer_wallet_id' => $payee->user->wallet->id,
            'payee_wallet_id' => $payer->user->wallet->id
        ];

        $request = $this->actingAs($application)->post(route('createTransaction'), $payload);

        $request->assertResponseStatus(422);
    }

    public function testeWalletsTransactionExist()
    {
        $application = Application::factory()->create();

        $payload = [
            'value' => 100,
            'payer_wallet_id' => 'string-simulation-uuid-wallet-payer',
            'payee_wallet_id' => 'string-simulation-uuid-wallet-payee'
        ];

        $request = $this->actingAs($application)->post(route('createTransaction'), $payload);

        $request->assertResponseStatus(422);
    }

    public function testePayerWalletExists()
    {
        $application = Application::factory()->create();
        $payer = PersonUser::factory()->create();

        $payload = [
            'value' => 100,
            'payer_wallet_id' => 'string-simulation-uuid-wallet-payer',
            'payee_wallet_id' => $payer->user->wallet->id
        ];

        $request = $this->actingAs($application)->post(route('createTransaction'), $payload);

        $request->assertResponseStatus(422);
    }

    public function testePayeeWalletExists()
    {
        $application = Application::factory()->create();
        $payee = CorporateUser::factory()->create();

        $payload = [
            'value' => 100,
            'payer_wallet_id' => 'string-simulacao-id-carteira-pagador',
            'payee_wallet_id' => $payee->user->wallet->id
        ];

        $request = $this->actingAs($application)->post(route('createTransaction'), $payload);

        $request->assertResponseStatus(422);
    }

    public function testePayerIsCompany()
    {
        $application = Application::factory()->create();
        $payer = PersonUser::factory()->create();
        $payee = CorporateUser::factory()->create();

        $payload = [
            'value' => 100,
            'payer_wallet_id' => $payee->user->wallet->id,
            'payee_wallet_id' => $payer->user->wallet->id
        ];

        $request = $this->actingAs($application)->post(route('createTransaction'), $payload);

        $request->assertResponseStatus(422);
    }

    public function testeInsufficientValutToMakeTransaction()
    {
        $application = Application::factory()->create();
        $payer = PersonUser::factory()->create();
        $payee = CorporateUser::factory()->create();

        $payload = [
            'value' => 100,
            'payer_wallet_id' => $payee->user->wallet->id,
            'payee_wallet_id' => $payer->user->wallet->id
        ];

        $request = $this->actingAs($application)->post(route('createTransaction'), $payload);

        $request->assertResponseStatus(422);
    }

    public function testeSuccessTransaction()
    {
        $application = Application::factory()->create();
        $payer = PersonUser::factory()->create();
        $payee = CorporateUser::factory()->create();

        $payer->user->wallet->credit(100);

        $payload = [
            'value' => 100,
            'payer_wallet_id' => $payer->user->wallet->id,
            'payee_wallet_id' => $payee->user->wallet->id
        ];

        $request = $this->actingAs($application)->post(route('createTransaction'), $payload);

        $request->assertResponseStatus(200);
    }
}
