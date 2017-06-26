<?php

namespace Maenbn\OpenAmAuthLaravel\Providers;


use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Maenbn\OpenAmAuth\Contracts\OpenAm;
use Maenbn\OpenAmAuthLaravel\Contracts\UserMapper;

class OpenAmUserProvider implements UserProvider
{
    /**
     * @var OpenAm
     */
    private $openAm;

    /**
     * @var Authenticatable
     */
    private $user;

    /**
     * @var UserMapper
     */
    private $userMapper;

    /**
     * OpenAmUserProvider constructor.
     * @param OpenAm $openAm
     * @param Authenticatable $user
     * @param UserMapper $userMapper
     */
    public function __construct(OpenAm $openAm, Authenticatable $user, UserMapper $userMapper)
    {
        $this->openAm = $openAm;
        $this->user = $user;
        $this->userMapper = $userMapper;
    }


    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        if($this->openAm->setTokenId($identifier)->validateTokenId()){
            $this->openAm->setUser();
            return $this->userMapper->map($this->openAm, $this->user);
        }

        return null;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return null;
        }

        if($this->openAm->authenticate($credentials['username'], $credentials['password'])){
            return $this->userMapper->map($this->openAm, $this->user);
        }

        return null;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return !is_null($user->getAuthIdentifier());
    }
}