<?php

namespace Maenbn\OpenAmAuthLaravel\Factories;


use Illuminate\Database\Eloquent\Model;
use Maenbn\OpenAmAuthLaravel\OpenAmUser;

class ModelFactory
{
    /**
     * @param Model|null $eloquentModel
     * @return OpenAmUser|Model
     */
    public static function create(Model $eloquentModel = null)
    {
        if(is_null($eloquentModel)){
            return new OpenAmUser();
        }

        return new $eloquentModel;
    }
}