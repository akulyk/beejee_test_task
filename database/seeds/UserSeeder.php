<?php

use app\helpers\HashHelper;
use app\models\User;
use database\traits\DbConfigTrait;
use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    use DbConfigTrait;

    public function run()
    {
        $users = require __DIR__ . '/../../tests/Fixtures/data/users.php';

        foreach ($users as $user) {
            User::create([
                'username' => $user['username'],
                'password' => $user['password'],
                'token' => HashHelper::generate()
            ]);
        }
    }
}
