<?php

namespace Dmn\Cmn\Middleware;

use Closure;
use Dmn\Exceptions\TokenExpiredException as DmnTokenExpiredException;
use Dmn\Exceptions\UnauthorizedException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthPayload
{
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
    public function __construct(Auth $auth)
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
            JWTAuth::parseToken()->payload();
        } catch (TokenExpiredException $exception) {
            throw new DmnTokenExpiredException();
        } catch (JWTException $exception) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
