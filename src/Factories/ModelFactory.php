<?php

namespace Maenbn\OpenAmAuthLaravel\Factories;


use Illuminate\Database\Eloquent\Model;
use Maenbn\OpenAmAuthLaravel\OpenAmUser;

class ModelFactory
{
    /**
     * @param string|null $eloquentModel
     * @return Model|OpenAmUser
     * @throws \Exception
     */
    public static function create($eloquentModel = null)
    {
        if(is_null($eloquentModel)){
            return new OpenAmUser();
        }

        $eloquentModel = new $eloquentModel;

        if(!$eloquentModel instanceof Model){
            throw new \Exception('Model provided is not an Eloquent Model');
        }

        return new $eloquentModel;
    }
}