<?php

namespace app\repositories\auth;


use app\models\User;

class UserRepository
{
    /**
     * @param int $id
     * @return User|null
     */
    public function getById(int $id)
    {
        return User::find($id);
    }

    /**
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return User::all();
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username)
    {
        return User::where('username', $username)->first();
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function findByToken(string $token)
    {
        return User::where('token', $token)->first();
    }
}
