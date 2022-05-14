<?php

namespace Bitinflow\Accounts\Http\Middleware;

use Bitinflow\Accounts\Exceptions\MissingScopeException;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckScopes
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$scopes
     * @return Response
     *
     * @throws AuthenticationException|MissingScopeException
     */
    public function handle($request, $next, ...$scopes)
    {
        if (!$request->user() || !$request->user()->bitinflowToken()) {
            throw new AuthenticationException;
        }

        foreach ($scopes as $scope) {
            if (!$request->user()->bitinflowTokenCan($scope)) {
                throw new MissingScopeException($scope);
            }
        }

        return $next($request);
    }
}
