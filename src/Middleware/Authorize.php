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
     * @param mixed ...$ability
     * @return mixed
     *
     * @throws ForbiddenException
     */
    public function handle($request, Closure $next, ...$ability)
    {
        $this->defineGate();
        foreach ($ability as $item) {
            $hasAbility[] = $this->gate->allows($item);
        }
        if (!in_array(true, $hasAbility ?? [])) {
            throw new ForbiddenException();
        }

        return $next($request);
    }

    /**
     * Define user permissions on gate
     */
    protected function defineGate(): void
    {
        $scope = request()->user()->scope ?? null;
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
            array_walk($access, function ($value) use ($permission) {
                $value = $this->translateAccess($value) . "_$permission";
                $this->gate->define($value, function () {
                    return true;
                });
            });
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
