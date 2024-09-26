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
    $AUTH_USER = env('SHIELD_USER');
    $AUTH_PASS = env('SHIELD_PASSWORD');

    $request->headers->set('Cache-Control', 'no-cache, must-revalidate, max-age=0');

    $credentials = $request->getUser() && $request->getPassword();

    if (!$credentials || $request->getUser() !== $AUTH_USER || $request->getPassword() !== $AUTH_PASS) {
      return response('Not Authorized', 401)
        ->header('WWW-Authenticate', 'Basic realm="Access denied"');
    }
    return $next($request);
  }
}