<?php

namespace Dmn\Cmn\Middleware;

use Closure;
use Dmn\Exceptions\ForbiddenException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Middleware\Authorize as BaseAuthorize;

class Authorize extends BaseAuthorize
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param string $ability
     * @param array|null ...$models
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws ForbiddenException
     */
    public function handle($request, Closure $next, $ability, ...$models)
    {
        try {
            $this->defineGate();
            $this->gate->authorize($ability, $this->getGateArguments($request, $models));
        } catch (AuthorizationException $exception) {
            throw new ForbiddenException();
        }

        return $next($request);
    }

    /**
     * Define user permissions on gate
     */
    protected function defineGate(): void
    {
        $userPermissions = auth()->payload()['scope'];
        $userPermissions = explode(' ', $userPermissions) ?? [];

        foreach ($userPermissions as $userPermission) {
            $this->gate->define($userPermission, function () {
                return true;
            });
        }
    }
}
