<?php

namespace app\http\controllers\auth;


use app\exceptions\auth\AuthException;
use app\http\controllers\Controller;
use app\http\requests\auth\AuthRequest;
use app\services\auth\AuthService;
use Exception;
use Illuminate\Http\RedirectResponse;


class UserController extends Controller
{
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        parent::__construct();
    }

    /**
     * @return RedirectResponse|string
     */
    public function login()
    {
        if ($this->authService->user()) {
            $this->session->getFlashBag()->set('warning','You are already logged in!');
            return $this->redirect('/');
        }

        return $this->render('login',[
            'username' => implode(PHP_EOL,$this->session->getFlashBag()->get('username')),
        ]);

    }

    /**
     * @param AuthRequest $request
     * @return RedirectResponse
     */
    public function makeLogin(AuthRequest $request){

        try{
            if($this->authService->login($request->post('username'),$request->post('password'))){
                $this->session->getFlashBag()->set('success','You have logged in!');
                return $this->redirect('/');
            }
        }catch (Exception $e){
            $this->session->getFlashBag()->set('danger', $e->getMessage());
            $this->session->getFlashBag()->set('username',$request->post('username'));

            return $this->redirect('/login');
        }
    }

    /**
     * @return RedirectResponse
     */
    public function logout()
    {
        if($this->authService->logout()){
            $this->session->getFlashBag()->set('warning','You are logged out!');
            return $this->redirect('/');
        }
    }

}
