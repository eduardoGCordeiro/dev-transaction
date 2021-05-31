<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Exceptions\Auth\AuthException;
use App\Exceptions\Auth\AuthRepositoryException;

class AuthRepository
{
    private $data;

    public function makeLogin($data): string
    {
        $this->data = (object) $data;

        try {
            $token = Auth::attempt([
                'id' => $this->data->id,
                'password' => $this->data->password
            ]);

            if (!$token) {
                throw new AuthException('Invalid id/password', 401);
            }

            return $token;
        } catch (AuthException $exception) {
            throw new AuthRepositoryException($exception->getMessage(), $exception->getCode());
        }
    }
}
