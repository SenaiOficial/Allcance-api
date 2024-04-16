<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Suggestions;

class SuggestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Suggestions::create([
        'user' => 'Allcance',
        'content' => 'Sejam todos bem-vindos á plataforma Allcance!',
        'approved' => 1,
      ]);
    }
}
