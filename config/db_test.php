<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 24.01.19
 * Time: 19:36
 */

use Phinx\Config\Config;

$config = Config::fromYaml(__DIR__ . '/../phinx.yml')
    ->getEnvironment('test');

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