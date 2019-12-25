<?php

namespace app\services\auth;

use app\exceptions\auth\UnauthorizedHttpException;
use app\helpers\HashHelper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CsrfService
{
    public const CSRF_TOKEN_NAME = 'csrf_token';
    protected const CSRF_TOKEN_EXPIRE_NANE = 'csrf_token_expire';

    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Session
     */
    private $session;

    protected $token;

    /**
     * AuthService constructor.
     * @param Request $request
     * @param Session $session
     */
    public function __construct(Request $request,
                                Session $session)
    {
        $this->request = $request;
        $this->session = $session;
    }

    public function setToken($token){
        $this->token = $token;
        $this->session->set(self::CSRF_TOKEN_EXPIRE_NANE,time() + 60*60);
        $this->session->set(self::CSRF_TOKEN_NAME,$token);
    }

    public function getToken(){
        if($this->token){
            return $this->token;
        }
       $expire = $this->session->get(self::CSRF_TOKEN_EXPIRE_NANE,0);
       if(time() < $expire) {
           return $this->session->get(self::CSRF_TOKEN_NAME);
       }
       return null;
    }

    public function validateToken(){
        $token = $this->request->post(self::CSRF_TOKEN_NAME);
        return $token === $this->getToken();
    }

    public function getOrGenerateToken(){
        if(! $token = $this->getToken()){
            $token = $this->generateToken();
        }
        return $token;
    }

    public function generateToken($length = 32){
        $token = HashHelper::crypt(HashHelper::generate($length));
        $this->setToken($token);
        return $token;
    }

    /**
     * @throws UnauthorizedHttpException
     */
    public function guard()
    {
        if (!$this->validateToken()) {
            $this->generateToken();
            throw new UnauthorizedHttpException([
                'error' => $message = 'Bad request!'
            ], $message);
        }
    }
}
