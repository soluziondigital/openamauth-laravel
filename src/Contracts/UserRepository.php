<?php

namespace Maenbn\OpenAmAuthLaravel\Contracts;

use Illuminate\Database\Eloquent\Model;

interface UserRepository
{

    /**
     * @param $uid
     * @return Model
     */
    public function findByUid($uid);
}