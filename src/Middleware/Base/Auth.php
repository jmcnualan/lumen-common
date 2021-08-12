<?php

namespace Dmn\Cmn\Middleware\Base;

use Closure;
use Dmn\Cmn\Middleware\Traits\CheckExpiration;
use Dmn\Exceptions\TokenExpiredException as DmnTokenExpiredException;
use Dmn\Exceptions\UnauthorizedException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

abstract class Auth
{
    use CheckExpiration;

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            $this->auth();
        } catch (TokenExpiredException $exception) {
            if (true === $this->checkExpiration()) {
                throw new DmnTokenExpiredException();
            }
        } catch (JWTException $exception) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }

    /**
     * Auth
     *
     * @return void
     */
    abstract public function auth();
}
