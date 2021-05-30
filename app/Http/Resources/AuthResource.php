<?php

namespace App\Http\Resources;

use App\Contracts\Resource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Auth;

class AuthResource implements Arrayable
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray()
    {
        return [
            'token' => $this->token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }
}
