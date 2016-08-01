<?php

namespace Vinkas\Visa\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfUnsecured
{
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
    if ( (!$request->secure()) && config('vinkas.visa.force_https')) {
      return redirect()->secure($request->path());
    }

    return $next($request);
  }
}
