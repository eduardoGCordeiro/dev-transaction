<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Exceptions\User\UserException;
use App\Exceptions\User\UserRepositoryException;

class UserRepository
{
    private $data;

    public function saveUser(object $data): User
    {
        try {
            $this->data = (object) $data;

            return DB::transaction(function () {
                $user = new User();
                $user->name = $this->data->name;
                $user->email = $this->data->email;
                $user->password = Hash::make($this->data->password);
                $user->save();

                $user->createSpecialization($this->data->document_type)->create([
                    $this->data->document_type => $this->data->document
                ]);
                $user->wallet()->create([]);

                return $user;
            });
        } catch (UserException $exception) {
            throw new UserRepositoryException($exception->getMessage(), 422);
        }
    }
}
