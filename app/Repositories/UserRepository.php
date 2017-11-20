<?php

namespace App\Repositories;

use App\Repositories\UserInterface as UserInterface;
use App\User;

class UserRepository implements UserInterface
{
    /** @var User $user */
    public $user;

    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->user->all();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getById($id)
    {
        return $this->user->find($id);
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->user->create($attributes);
    }

    /**
     * @param       $id
     * @param array $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        return $this->user->find($id)->update($attributes);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->user->find($id)->delete();
    }

    /**
     * @param $id
     * @param $amount
     *
     * @return mixed
     */
    public function deposit($id, $amount)
    {
        return $this->user->updateOrCreate(['id' => $id], ['id' => $id])->increment('balance', $amount);
    }

    /**
     * @param $id
     * @param $amount
     *
     * @return mixed
     */
    public function withdraw($id, $amount)
    {
        return $this->user->whereId($id)->decrement('balance', $amount);
    }

}