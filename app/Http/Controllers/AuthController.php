<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AuthResource;
use App\Repositories\AuthRepository;
use App\Exceptions\Auth\AuthRepositoryException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    private const RULES = [
       'password' => 'required|string|max:255',
       'id' => 'required|max:255',
    ];

    private $repository;

    /**
    * @var UserRepository
    */
    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function login(Request $request)
    {
        $this->validate($request, self::RULES);

        try {
            $fields = $request->only(['id', 'password']);
            $token = $this->repository->makeLogin($fields);

            return response()->json(new AuthResource($token), 200);
        } catch (AuthRepositoryException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        } catch (\Exception $exception) {
            dd($exception);
            Log::critical('[User error]', [
                'message' => $exception->getMessage()
            ]);

            return response()->json(['message' => 'Login failed, please try again later!'], 500);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Application successfully signed out'], 200);
    }

    public function refresh()
    {
        return response()->json(new AuthResource(Auth::refresh()), 200);
    }
}
