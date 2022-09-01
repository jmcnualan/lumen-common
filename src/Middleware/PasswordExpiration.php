<?php

namespace Dmn\Cmn\Middleware;

use Dmn\Exceptions\PasswordExpiredException;

class PasswordExpiration
{
    /**
     * @throws \Throwable
     */
    public function handle($request, \Closure $next)
    {
        throw_if(request()->user()->password_expired, new PasswordExpiredException());

        return $next($request);
    }

}
