<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    private const RULES = [
       'password' => 'required|string|max:255',
       'email' => 'required|email|max:255',
    ];

    public function login(Request $request)
    {
        $this->validate($request, self::RULES);

        $token = Auth::attempt($request->only(['email', 'password']));

        if (!$token) {
            return response()->json(['message' => 'Invalid email/password'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
