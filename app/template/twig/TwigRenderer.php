<?php

namespace app\template\twig;

use app\http\requests\AbstractRequest;
use app\services\auth\AuthService;
use app\services\auth\CsrfService;
use app\template\TemplateRenderer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;
use Twig\Markup;

class TwigRenderer implements TemplateRenderer
{
    private $twig;
    private $extension;

    public function __construct(Environment $twig, $extension)
    {
        $this->twig = $twig;
        $this->extension = $extension;

    }

    public function render($name, array $params = []): string
    {
        $this->registerFunctions();
        return $this->twig->render($name . $this->extension, $params);
    }

    protected function registerFunctions()
    {
        $this->addIsGuest();
        $this->addFlashMessages();
        $this->addCsrfToken();
        $this->addValidationErrors();
        $this->addFormValues();
        $this->addPageUrl();
        $this->addSortUrl();
    }

    protected function addIsGuest()
    {
        $function = new \Twig\TwigFunction('isGuest', function () {
            /**@var AuthService $auth */
            $auth = app()->make(AuthService::class);
            return !$auth->isAuth();
        });
        $this->twig->addFunction($function);
    }

    protected function addCsrfToken()
    {
        /**
         * @var CsrfService $csrf
         */
        $csrf = app()->make(CsrfService::class);
        $token = $csrf->generateToken();
        $this->twig->addGlobal('csrf_token_name', CsrfService::CSRF_TOKEN_NAME);
        $this->twig->addGlobal('csrf_token', $token);
        $function = new \Twig\TwigFunction('csrf', function () use ($token) {
            $input = '<input type="hidden" name="' . CsrfService::CSRF_TOKEN_NAME . '"  value="' . $token . '"/>';
            return new Markup($input, "UTF-8");
        });
        $this->twig->addFunction($function);

    }

    protected function addFlashMessages()
    {
        /**@var Session $session */
        $session = app()->get(Session::class);
        $flashBag = $session->getFlashBag();

        $function = new \Twig\TwigFunction('alert', function ($type = null) use ($flashBag) {
            $alertTypes = ['success','warning','info','danger'];
            if (empty($flashBag->peekAll()) || $type && empty($alertTypes[$type])) {
                return;
            }
            $flash = function ($type, $message) {
                $html = '<div class="alert alert-' . $type . ' alert-dismissible" role="alert">';
                $html .= ' <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>';
                $html .= $message;
                $html .= '</div>';
                return $html;
            };
            $content = '';
            if($type){
                foreach ($flashBag->get($type) as $messages){
                    foreach ($messages as $message){
                        $content .= $flash($type,$message);
                    }
                }
            }else{
                foreach ($alertTypes as $type){
                    if(!empty($messages = $flashBag->get($type))) {
                        foreach ($messages as $message) {
                            $content .= $flash($type, $message);
                        }
                    }
                }
            }
            return new Markup($content, "UTF-8");
        });
        $this->twig->addFunction($function);
    }
    protected function addValidationErrors(){
        /**@var Session $session */
        $session = app()->get(Session::class);
        $flashBag = $session->getFlashBag();
        $function = new \Twig\TwigFunction('error', function ($field) use ($flashBag) {
            return implode(PHP_EOL, $flashBag->get(AbstractRequest::FLASH_ERROR_PREFIX . $field));
        });
        $this->twig->addFunction($function);
    }

    protected function addFormValues(){
        /**@var Session $session */
        $session = app()->get(Session::class);
        $flashBag = $session->getFlashBag();

        $function = new \Twig\TwigFunction('value', function ($field) use ($flashBag) {
            return implode(PHP_EOL, $flashBag->get(AbstractRequest::FLASH_VALUE_PREFIX . $field));
        });
        $this->twig->addFunction($function);
    }

    protected function addPageUrl(){
        /** @var Request $request */
        $request = app()->get(Request::class);
        $function = new \Twig\TwigFunction('pageUrl', function ($number) use ($request) {
            $queryParams = $request->query();
            $queryParams['page'] = $number;
            return '/?' . http_build_query($queryParams);
        });
        $this->twig->addFunction($function);
    }

    protected function addSortUrl(){
        /** @var Request $request */
        $request = app()->get(Request::class);
        $function = new \Twig\TwigFunction('sortUrl', function ($field) use ($request) {
            $queryParams = $request->query();
            if(($sort = $request->get('sort'))){
                if($sort === $field) {
                    $field = '-' . $field;
                }elseif ($sort === '-' . $field){
                    $field = ltrim($field,'-');
                }
            }
            $queryParams['sort'] = $field;
            return '/?' . http_build_query($queryParams);
        });
        $this->twig->addFunction($function);
    }
}
