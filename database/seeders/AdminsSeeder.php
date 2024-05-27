<?php

namespace Database\Seeders;

use App\Models\UserAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserAdmin::create([
            'institution_name' => 'Allcance',
            'telephone' => env('ALLCANCE_TELEPHONE'),
            'cnpj' => env('ALLCANCE_CNPJ'),
            'email' => env('ALLCANCE_EMAIL'),
            'password' => bcrypt(env('ALLCANCE_PASSWORD')),
            'custom_token' => Str::random(60),
            'refresh_token' => Str::random(60),
            'is_institution' => false,
            'is_admin' => true,
        ]);

        UserAdmin::create([
            'institution_name' => 'Comped',
            'telephone' => env('COMPED_TELEPHONE'),
            'cnpj' => env('COMPED_CNPJ'),
            'email' => env('COMPED_EMAIL'),
            'password' => bcrypt(env('COMPED_PASSWORD')),
            'custom_token' => Str::random(60),
            'refresh_token' => Str::random(60),
            'is_institution' => false,
            'is_admin' => true,
        ]);
    }
}
