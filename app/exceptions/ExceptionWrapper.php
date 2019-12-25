<?php namespace app\exceptions;

use app\template\TemplateRenderer;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class ExceptionWrapper
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Router $router, Request $request)
    {
        $this->router = $router;
        $this->request = $request;
    }

    public function wrap()
    {
        try {
            $response = $this->router->dispatch($this->request);
            $response->send();
        } catch (AbstractException $exception) {
            http_response_code($exception->getStatusCode());
            $renderer = app()->get(TemplateRenderer::class);
            $view = '/error/error';
            if($exception->getStatusCode() == 404){
                $view = '/error/404';
            }elseif ($exception->getStatusCode() == 403){
                $view = '/error/403';
            }
            header('Content-Type: text/html');
            echo $renderer->render($view,[
                'message'=>$exception->getMessage(),
            ]);
        } catch (\Exception $exception) {
            http_response_code($exception->getCode());
            /**@var \app\template\TemplateRenderer $renderer */
            $renderer = app()->get(TemplateRenderer::class);
            header('Content-Type: text/html');
            echo $renderer->render('/error/error',[
                'message'=>$exception->getMessage(),
            ]);

        }
    }
}
