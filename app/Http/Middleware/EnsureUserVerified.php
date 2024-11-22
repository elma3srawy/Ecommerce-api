<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserVerified
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return auth('admin')->check() ? $this->ensureEmailVerified($request , $next) : $this->ensureMobileVerified($request , $next);
    }

    private function ensureEmailVerified(Request $request, Closure $next)
    {
        if(!auth()->user()->hasVerifiedEmail())
        {
            return $this->forbiddenResponse(
                message: 'Your email address is not verified. Please verify your email to continue.'
            );
        }
        return $next($request);
    }

    private function ensureMobileVerified(Request $request, Closure $next)
    {
        if(!auth()->user()->hasVerifiedMobile())
        {
            return $this->forbiddenResponse(
                 message: 'Your mobile number is not verified. Please verify your mobile number to proceed.'
            );
        }
        return $next($request);
    }


}
