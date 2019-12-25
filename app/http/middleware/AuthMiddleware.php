<?php namespace app\http\middleware;

use app\services\auth\AuthService;
use Closure;
use Illuminate\Http\Request;


class AuthMiddleware
{
    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \app\exceptions\auth\UnauthorizedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->authService->guard();

        return $next($request);
    }
}
