<?php namespace app\http\controllers;

use app\template\TemplateRenderer;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Session\Session;

class Controller
{
    /**
     * @var TemplateRenderer
     */
    protected $renderer;
    /**
     * @var Redirector
     */
    protected $redirect;
    /**
     * @var Session
     */
    protected $session;

    public function __construct()
    {
        $this->redirect = app()->get('redirect');
        $this->renderer = app()->get(TemplateRenderer::class);
        $this->session =  app()->get(Session::class);
    }

    public function render($view, $params = []){
        return $this->renderer->render($view, $params);
    }

    public function redirect($path, $status = 302, $headers = []){
        return $this->redirect->to($path, $status, $headers);
    }
}
