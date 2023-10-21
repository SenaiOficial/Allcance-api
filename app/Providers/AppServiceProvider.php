<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         Validator::extend('valid_cpf', function ($attribute, $value, $parameters, $validator) {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $cpf = $phoneUtil->parse($value, "BR");
            return $phoneUtil->isValidNumber($cpf);
        } catch (\libphonenumber\NumberFormatException $e) {
            return false;
        }
    });
    }
}
