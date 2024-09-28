<?php

return [

    'basic' => [
        'shield_user' => env('SHIELD_USER'),
        'shield_password' => env('SHIELD_PASSWORD'),
    ],

    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'pcd_users',
        ],

        'standar' => [
            'driver' => 'jwt',
            'provider' => 'standar_user',
        ],

        'admin' => [
            'driver' => 'jwt',
            'provider' => 'admin_user',
        ],
    ],

    'providers' => [
        'pcd_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\UserPcd::class,
        ],

        'standar_user' => [
            'driver' => 'eloquent',
            'model' => App\Models\UserStandar::class,
        ],

        'admin_user' => [
            'driver' => 'eloquent',
            'model' => App\Models\UserAdmin::class,
        ],
    ],

    'passwords' => [
        'pcd_users' => [
            'provider' => 'pcd_users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'standar_user' => [
        'provider' => 'standar_user',
        'table' => 'password_resets_standar',
        'expire' => 60,
        'throttle' => 60,
    ],

    'admin_user' => [
        'provider' => 'admin_user',
        'table' => 'password_resets_admin',
        'expire' => 60,
        'throttle' => 60,
    ],

    'password_timeout' => 10800,

];
