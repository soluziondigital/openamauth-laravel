<?php

namespace Maenbn\OpenAmAuthLaravel\Repositories;


use Illuminate\Database\Eloquent\Model;
use Maenbn\OpenAmAuthLaravel\Contracts\UserRepository;

class UserEloquent implements UserRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var
     */
    private $uidName;

    public function __construct(Model $user, $uidName)
    {
        $this->model = $user;
        $this->uidName = $uidName;
    }

    /**
     * @param $uid
     * @return Model
     */
    public function findByUid($uid)
    {
        return $this->model->where($this->uidName, $uid)->first();
    }
}