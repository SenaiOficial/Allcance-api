<?php

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
