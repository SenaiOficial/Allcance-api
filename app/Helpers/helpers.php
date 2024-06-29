<?php
use Illuminate\Support\Facades\Hash;

if (!function_exists('guards')) {
    function guards()
    {
        $guards = array_keys(config('auth.guards'));
        $guards = array_slice($guards, 0, -1);

        return $guards;
    }
}

if (!function_exists('getActiveGuard')) {
    function getActiveGuard()
    {

        foreach (guards() as $guard) {
            if (auth()->guard($guard)->check()) return $guard;
        }

        return null;
    }
}

if (!function_exists('makeRandomToken')) {
    function makeRandomToken()
    {
        $token = Str::random(5);

        $token = hash('sha256', $token);

        return $token;
    }
}

if (!function_exists('checkUserPassword')) {
    function checkUserPassword(string $targetPassword, string $userPassword): bool
    {
        if (!Hash::check($targetPassword, $userPassword)) return true;
        
        return false;
    }
}
