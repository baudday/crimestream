<?php

namespace App\Http\Middleware;

use Closure;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if ($request->user() && ! $request->user()->subscribed('main')) {
        $request->session()->flash('error', 'You must be subscribed to use this feature. It\'s only $5/mo!');
        return redirect('account');
      }

      return $next($request);
    }
}