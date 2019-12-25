<?php

namespace app\services\auth;

use app\exceptions\auth\AuthException;
use app\exceptions\auth\UnauthorizedHttpException;
use app\models\User;
use app\repositories\auth\UserRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthService
{
    protected const USER_ID = 'user_id';
    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Session
     */
    private $session;

    /**
     * AuthService constructor.
     * @param Request $request
     * @param UserRepository $userRepository
     * @param Session $session
     */
    public function __construct(Request $request,
                                UserRepository $userRepository,
                                Session $session)
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    /**
     * @param string $username
     * @param string $password
     * @return User|null
     * @throws AuthException
     */
    public function login(string $username, string $password)
    {
        if (!$this->isAuth()) {
            $user = $this->userRepository->findByUsername($username);
            if (!is_null($user) && $user->comparePassword($password)) {
                $user->save();
                $this->user = $user;
                $this->session->set(self::USER_ID,$user->id);
                return true;
            }
        }

        throw new AuthException(
            ['username' => $message = 'Incorrect credentials!'],
            $message
        );
    }

    /**
     * @return bool|array
     */
    public function logout()
    {
        if ($this->isAuth()) {
            $user = $this->user();
            $user->eraseToken();
            if ($user->save()) {
                $this->session->remove(self::USER_ID);
                return true;
            }
        }

        return false;
    }

    /**
     * @return User|null
     */
    public function user()
    {
        if($this->user){
            return $this->user;
        }
        if ($userId = $this->session->get(self::USER_ID)) {
            $this->user = $this->userRepository->getById($userId);
            return $this->user;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isAuth(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @throws UnauthorizedHttpException
     */
    public function guard()
    {
        if (!$this->isAuth()) {
            throw new UnauthorizedHttpException([
                'error' => $message = 'You unauthorized!'
            ], $message);
        }
    }
}
