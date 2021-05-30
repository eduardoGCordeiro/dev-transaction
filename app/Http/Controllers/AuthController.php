<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\AuthResource;

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

        return response()->json(new AuthResource($token), 200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'User successfully signed out'], 200);
    }

    public function refresh()
    {
        return response()->json(new AuthResource(Auth::refresh()), 200);
    }
}
