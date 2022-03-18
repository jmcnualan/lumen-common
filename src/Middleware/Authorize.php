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
        $scope = auth()->payload()['scope'];
        if ($scope) {
            $this->definePermissions(explode(' ', $scope));
        }
    }

    /**
     * @param array $userPermissions
     * @return void
     */
    public function definePermissions(array $userPermissions): void
    {
        foreach ($userPermissions as $userPermission){
            list($permission, $access) =  explode(':', $userPermission, 2);
            $access = str_split($access);
            array_walk($access, function ($value, $key) use ($permission) {
                $value = $this->translateAccess($value) . "_$permission";
                $this->gate->define($value, function () {
                    return true;
                });
            });
            $scope = array_merge($scope, $access);
        }
    }

    /**
     * @param string $access
     * @return string
     */
    public function translateAccess(string $access): string
    {
        return match ($access) {
            'r' => 'view',
            'w' => 'create',
            'u' => 'update',
            'd' => 'delete',
            'x' => 'execute'
        };
    }
}