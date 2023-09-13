<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Users;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Users::class;
    
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone_number' => $this->faker->phoneNumber,
            'cpf' => $this->faker->unique()->numerify('###########'),
            'date_of_birth' => $this->faker->date,
            'marital_status' => $this->faker->randomElement(['Solteiro', 'Casado', 'Divorciado']),
            'gender' => $this->faker->randomElement(['Homem', 'Mulher']),
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('Senha123!'),
        ];
    }
}
