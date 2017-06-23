<?php

namespace Maenbn\OpenAmAuthLaravel\Factories;

use Illuminate\Database\Eloquent\Model;
use Maenbn\OpenAmAuthLaravel\Repositories\UserEloquent;

class UserRepositoryFactory
{
    /**
     * @param $eloquentUidName
     * @param Model $user
     * @return UserEloquent|null
     */
    public static function create($eloquentUidName, Model $user = null)
    {
        if($user instanceof Model){
            /** @var Model $user */
            $user = new $user;
            return new UserEloquent($user, $eloquentUidName);
        }

        return null;
    }
}