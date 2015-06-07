<?php namespace App\Http\Middleware;

use App\Models\User;
use Closure;

/*
 * The purpose of this middleware is to make sure
 * 1. The user is logged in.
 * 2. The user has the role of 'owner'
 */
class HasOwnerRole {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
  {
    $user = $request->user();
    if ($user && $user->hasRole('owner')) return $next($request);

    return redirect('/');
  }
}
