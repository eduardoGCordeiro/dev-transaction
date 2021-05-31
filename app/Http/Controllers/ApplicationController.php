<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ApplicationRepository;
use App\Exceptions\Application\ApplicationRepositoryException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    private const RULES = [
        'name' => 'required|string|max:255',
        'password' => 'required|string|max:255',
    ];

    private $repository;

    /**
    * @var UserRepository
    */
    public function __construct(ApplicationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(Request $request)
    {
        $this->validate($request, self::RULES);

        try {
            $fields = (object) $request->only(['name', 'password']);
            $application = $this->repository->handle($fields);

            return response()->json(['application' => $application, 'message' => 'User created!'], 201);
        } catch (ApplicationRepositoryException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        } catch (\Exception $exception) {
            Log::critical('[User error]', [
                'message' => $exception->getMessage()
            ]);

            return response()->json(['message' => 'Application Registration failed, please try again later!'], 500);
        }
    }
}
