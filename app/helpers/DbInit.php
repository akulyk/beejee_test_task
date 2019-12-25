<?php

namespace app\helpers;

use Illuminate\Database\Capsule\Manager as Capsule;

class DbInit
{
    /**
     * @var array
     */
    private $dbConfig;

    /**
     * @var Capsule
     */
    private $capsule;

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
        $this->init();
    }

    private function init()
    {
        $this->capsule = new Capsule;
        $this->capsule->addConnection($this->dbConfig);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    public function getCapsule(): Capsule
    {
        return $this->capsule;
    }
}
