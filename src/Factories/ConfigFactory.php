<?php

namespace Maenbn\OpenAmAuthLaravel\Factories;

use Maenbn\OpenAmAuth\Config;

class ConfigFactory
{

    /**
     * @param array $config
     * @return Config
     */
    public static function create(array $config)
    {
        if(is_null($config['uri'])){
            $config['uri'] = 'openam';
        }
        $configObject = new Config($config['domain'], $config['realm'], $config['uri']);
        $configObject->setCookieName($config['cookieName']);

        return $configObject;
    }
}