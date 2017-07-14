<?php

namespace Maenbn\OpenAmAuthLaravel\Mappers;

use Illuminate\Contracts\Auth\Authenticatable;
use Maenbn\OpenAmAuth\Contracts\OpenAm;
use Maenbn\OpenAmAuthLaravel\Contracts\UserRepository;

class UserMapper implements \Maenbn\OpenAmAuthLaravel\Contracts\UserMapper
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var string
     */
    private $eloquentGuestUid;

    public function __construct(UserRepository $userRepository = null, $eloquentGuestUid = null)
    {
        $this->userRepository = $userRepository;
        $this->eloquentGuestUid = $eloquentGuestUid;
    }

    /**
     * @param OpenAm $openAm
     * @param Authenticatable $user
     * @return Authenticatable
     */
    public function map(OpenAm $openAm, Authenticatable $user)
    {
        $attributes = $openAm->getUser();
        foreach ($attributes as $key => $value) {
            if(is_array($value) && count($value) == 1 && isset($value[0])){
                $value = $value[0];
            }
            $user->$key = $value;
        }
        $user->{$user->getAuthIdentifierName()} = $openAm->getTokenId();

        return $this->mapEloquentAttributes($user);
    }

    /**
     * @param Authenticatable $user
     * @return Authenticatable
     */
    private function mapEloquentAttributes(Authenticatable $user)
    {
        if(!is_null($this->userRepository)){
            foreach($this->getAttributes($user) as $key => $value){
                $user->$key = $value;
            }
        }
        return $user;
    }

    /**
     * @param $user
     * @return array
     */
    private function getAttributes(Authenticatable $user)
    {
        $user = $this->userRepository->findByUid($user->uid);
        if(is_null($user)){
            $user = $this->userRepository->findByUid($this->eloquentGuestUid);
        }
        return $user->toArray();
    }
}