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
    public function testePayerIsCompany()
    {
        $this->get('/');

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
}
