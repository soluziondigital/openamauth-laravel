<?php

namespace Maenbn\OpenAmAuthLaravel\Factories;


use Illuminate\Database\Eloquent\Model;
use Maenbn\OpenAmAuth\Contracts\OpenAm;
use Maenbn\OpenAmAuthLaravel\Providers\OpenAmUserProvider;

class UserProviderFactory
{
    /**
     * @param array $config
     * @param OpenAm $openAm
     * @return OpenAmUserProvider
     */
    public static function create(array $config, OpenAm $openAm)
    {
        $model = ModelFactory::create($config['eloquentUser']);
        $modelForRepository = $model;
        if(!$modelForRepository instanceof Model){
            $modelForRepository = null;
        }
        $userRepository = UserRepositoryFactory::create($config['eloquentUidName'], $modelForRepository);
        $userMapper = UserMapperFactory::create($userRepository);

        return new OpenAmUserProvider($openAm, $model, $userMapper);
    }
}