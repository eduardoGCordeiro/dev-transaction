<?php

namespace App\Http\Resources;

use App\Contracts\Resource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource implements Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'wallet' => $this->wallet,
            'type_user' => $this->specialization
        ];
    }
}
