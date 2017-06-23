<?php

namespace Maenbn\OpenAmAuthLaravel\Exceptions;


class ConfigNotFound extends \Exception
{

    protected $message = 'OpenAM config file not found. Please run php artisan vendor:publish.';

    public function __construct()
    {
        parent::__construct($this->message, 0, null);
    }
}