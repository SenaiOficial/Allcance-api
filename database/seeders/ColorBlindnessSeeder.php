<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ColorBlindness;

class ColorBlindnessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['description' => 'Deuteranopia'],
            ['description' => 'Protanopia'],
            ['description' => 'Tritanopia']
        ];

        ColorBlindness::insert($data);
    }
}
