<?php namespace app\http\middleware;

use app\services\auth\CsrfService;
use Closure;
use Illuminate\Http\Request;


class CsrfMiddleware
{
    /**
     * @var CsrfService
     */
    private $csrfService;

    public function __construct(CsrfService $csrfService)
    {
        $this->csrfService = $csrfService;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \app\exceptions\auth\UnauthorizedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->csrfService->guard();

        return $next($request);
    }
}
