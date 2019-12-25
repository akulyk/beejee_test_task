<?php

use app\helpers\DbInit;
use Illuminate\Container\Container;

/**
 * @return Container
 */
function app()
{
    return Container::getInstance();
}

/**
 * @param string $configName
 * @return \Illuminate\Database\Capsule\Manager
 */
function db(string $configName)
{
    return (new DbInit(config($configName)))->getCapsule();
}

/**
 * @param string $fileName
 * @return mixed
 */
function config(string $fileName)
{
    return require __DIR__ . '/config/' . $fileName;
}

function bootstrap(string $fileName)
{
    return require __DIR__ . '/bootstrap/' . $fileName;
}

/**
 * @param string $controllerClass
 * @param string $controllerMethod
 * @return string
 */
function action(string $controllerClass, string $controllerMethod)
{
    return sprintf('%s@%s', $controllerClass, $controllerMethod);
}
