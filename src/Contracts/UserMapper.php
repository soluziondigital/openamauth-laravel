<?php

namespace Maenbn\OpenAmAuthLaravel\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Maenbn\OpenAmAuth\Contracts\OpenAm;

interface UserMapper
{
    /**
     * @param OpenAm $openAm
     * @param Authenticatable $user
     * @return Authenticatable
     */
    public function map(OpenAm $openAm, Authenticatable $user);
}