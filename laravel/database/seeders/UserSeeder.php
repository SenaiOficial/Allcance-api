<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone_number' => '1234567890',
            'cpf' => '08843391909',
            'date_of_birth' => '2003-10-22',
            'marital_status' => 'Solteiro',
            'gender' => 'Homem',
            'email' => 'teste@example.com',
            'password' => bcrypt('Senha123!'),
        ]);
    }
}
