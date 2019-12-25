<?php

use app\exceptions\http\NotFoundHttpException;
use app\http\controllers\auth\UserController;
use app\http\controllers\HomeController;
use app\http\controllers\TodoController;
use app\http\middleware\AuthMiddleware;
use app\http\middleware\CsrfMiddleware;
use Illuminate\Routing\Router;

/** @var $router Router */
#index
$router->get('/', action(HomeController::class, 'index'));

$router->post('/todo/add', action(TodoController::class, 'add'));

#authorisation
$router->get('login', action(UserController::class, 'login'));
$router->post('login', action(UserController::class, 'makeLogin'))
    ->middleware(CsrfMiddleware::class);

$router->post('logout', action(UserController::class, 'logout'))
    ->middleware(AuthMiddleware::class)
    ->middleware(CsrfMiddleware::class);

#crud
$router->get('/todo/create', action(TodoController::class, 'create'));
$router->post('/todo/create', action(TodoController::class, 'add'))
    ->middleware(CsrfMiddleware::class);

$router->middleware([AuthMiddleware::class])->group(function (Router $router) {
    $router->get('/todo/{id}/finish', action(TodoController::class, 'finish'));

    $router->get('/todo/{id}/edit', action(TodoController::class, 'update'));
    $router->post('/todo/{id}/edit', action(TodoController::class, 'makeUpdate'))
        ->middleware(CsrfMiddleware::class);

    $router->get('/todo/{id}/delete', action(TodoController::class, 'delete'));

});

$router->any('{any}', function () {
    throw new NotFoundHttpException([
        'error' => $message = 'Page not found!'
    ], $message);
})->where('any', '(.*)');
