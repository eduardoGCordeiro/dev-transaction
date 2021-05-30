<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    private const RULES = [
        'value' => 'required|float',
        'payer_wallet_id' => 'required|max:255',
        'payee_wallet_id' => 'required|email|max:255',
    ];

    public function income(Request $request)
    {
        return response()->json(['message' => 'Invalid email/password'], 401);
    }

    public function outcome(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'User successfully signed out'], 200);
    }
}
