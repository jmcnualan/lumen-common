<?php

namespace Dmn\Cmn\Middleware;

use Dmn\Cmn\Middleware\Base\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate extends Auth
{
    /**
     * {@inheritDoc}
     */
    public function auth()
    {
        JWTAuth::parseToken()->authenticate();
    }
}
