<?php

use app\models\todo\Todo;
use database\traits\DbConfigTrait;
use Phinx\Seed\AbstractSeed;

class TodoSeeder extends AbstractSeed
{
    use DbConfigTrait;

    public function run()
    {
        $todos = require __DIR__ . '/../../tests/Fixtures/data/todos.php';
        $this->batchFill((new Todo())->getTable(), $todos);
    }

    protected function batchFill(string $tableName, $fixtures) {
        foreach ($fixtures as $data) {
            $this->fill($tableName, $data);
        }
    }

    protected function fill(string $tableName, array $data)
    {
        $this->capsule->getConnection()
            ->table($tableName)
            ->insert($data);
    }
}
