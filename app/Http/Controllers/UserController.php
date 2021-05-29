<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PersonUser;
use App\Models\CorporateUser;
use Illuminate\Support\Facades\DB;

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

    public function register(Request $request)
    {
        $this->validate($request, self::RULES);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            if ($request->input('document_type') == 'cpf') {
                $specialization = new PersonUser();
                $specialization->cpf = $request->input('cpf');
            }

            if ($request->input('document_type') == 'cnpj') {
                $specialization = new CorporateUser();
                $specialization->cpf = $request->input('cnpj');
            }

            $specialization->user_id = $user->id;
            $specialization->save();

            DB::commit();
            return response()->json(['user' => $user->with('specialization'), 'message' => 'User created!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'User Registration Failed!'], 500);
        }
    }
}
