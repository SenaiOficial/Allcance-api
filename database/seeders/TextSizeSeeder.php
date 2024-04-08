<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TextSize;

class TextSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $text = [
            ['description' => 'Texto muito pequeno'],
            ['description' => 'Texto pequeno'],
            ['description' => 'Texto normal'],
            ['description' => 'Texto grande'],
            ['description' => 'Texto muito grande']
        ];

        TextSize::insert($text);
    }
}
