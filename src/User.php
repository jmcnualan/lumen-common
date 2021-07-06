<?php

namespace Dmn\Cmn;

use Illuminate\Contracts\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Tymon\JWTAuth\Payload;

class User implements UserProvider
{
    /**
     * Construct
     *
     * @param Payload $payload
     */
    public function __construct(protected Payload $payload)
    {
        $this->boot();
    }

    /**
     * Boot
     *
     * @return void
     */
    protected function boot()
    {
        foreach ($this->payload->getClaims() as $claim) {
            $this->{$claim->getName()} = $claim->getValue();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveById($identifier)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function updateRememberToken(AuthAuthenticatable $user, $token)
    {
        return;
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveByCredentials(array $credentials)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function validateCredentials(AuthAuthenticatable $user, array $credentials)
    {
        return true;
    }
}
