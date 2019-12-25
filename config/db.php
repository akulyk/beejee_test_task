<?php

use Phinx\Config\Config;

$config = Config::fromYaml(__DIR__ . '/../phinx.yml')
    ->getEnvironment('dev');

return [
    'driver' => $config['adapter'],
    'host' => $config['host'],
    'database' => $config['name'],
    'username' => $config['user'],
    'password' => $config['pass'],
    'port' => $config['port'],
    'charset' => $config['charset'],
    'collation' => $config['collation'],
    'prefix' => '',
];
