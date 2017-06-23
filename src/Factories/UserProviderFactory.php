<?php

namespace Maenbn\OpenAmAuthLaravel\Factories;


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
        $userRepository = UserRepositoryFactory::create($config['eloquentUidName'], $config['eloquentUser']);
        $userMapper = UserMapperFactory::create($userRepository);

        return new OpenAmUserProvider($openAm, $model, $userMapper);
    }
}