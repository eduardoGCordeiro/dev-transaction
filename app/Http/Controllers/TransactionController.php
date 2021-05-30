<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\CheckBalancePayer;
use App\Rules\PayerUserType;
use Illuminate\Http\Request;

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

        return response()->json(['message' => 'Transaction complete with success!'], 200);
    }
}
