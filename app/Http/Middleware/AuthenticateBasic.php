<?php
namespace App\Http\Middleware;
use Closure;
class AuthenticateBasic
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
    $request->headers->set('Cache-Control', 'no-cache, must-revalidate, max-age=0');

    $credentials = $request->getUser() && $request->getPassword();

    if (!$credentials || $request->getUser() !== config('auth.basic.shield_user') || $request->getPassword() !== config('auth.basic.shield_password')) {
      return response('Not Authorized', 401)
        ->header('WWW-Authenticate', 'Basic realm="Access denied"');
    }

    return $next($request);
  }
}