<?php

namespace Dmn\Cmn\Middleware\Traits;

trait CheckExpiration
{
    /**
     * Check expiration
     *
     * @return bool
     */
    protected function checkExpiration(): bool
    {
        if (app()->environment('testing')) {
            return config('auth.check_expiration', true);
        }

        return true;
    }
}
