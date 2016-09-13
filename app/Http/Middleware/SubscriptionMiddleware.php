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
      if ($request->user() && ! $request->user()->subscribed('main') && $request->user()->email !== getenv('ADMIN_EMAIL')) {
        $request->session()->flash('error', 'You must be subscribed to use this feature. It\'s only $5 a month!');
        return redirect('account');
      }

      return $next($request);
    }
}
