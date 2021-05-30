<?php

namespace App\Contracts;

interface Resource
{
    public function toArray($request);
}
