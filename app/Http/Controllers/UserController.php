<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Exceptions\User\UserRepositoryException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private const RULES = [
        'name' => 'required|string|max:255',
        'password' => 'required|string|max:255',
        'email' => 'required|email|unique:users|max:255',
        'document_type' => 'required|in:cpf,cnpj',
        'cpf' => 'required_if:document_type,cpf|unique:person_users|size:11',
        'cnpj' => 'required_if:document_type,cnpj|unique:corporate_users|size:14'
    ];

    private $repository;

    /**
    * @var UserRepository
    */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(Request $request)
    {
        $this->validate($request, self::RULES);

        try {
            $fields = (object) $request->only(['name', 'email', 'password', 'document_type']);

            if ($request->input('cpf')) {
                $fields->document = $request->input('cpf');
            }

            if ($request->input('cnpj')) {
                $fields->document = $request->input('cnpj');
            }

            $user = $this->repository->saveUser($fields);

            return response()->json(['user' => new UserResource($user), 'message' => 'User created!'], 201);
        } catch (UserRepositoryException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        } catch (\Exception $exception) {
            Log::critical('[User error]', [
                'message' => $exception->getMessage()
            ]);

            return response()->json(['message' => 'User Registration failed, please try again later!'], 500);
        }
    }
}
