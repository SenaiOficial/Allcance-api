<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeficiencyTypes;

class DeficiencyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deficiencies = [
            ['description' => 'Física'],
            ['description' => 'Visual'],
            ['description' => 'Auditiva'],
            ['description' => 'Intelectual'],
            ['description' => 'Surdo cegueira'],
            ['description' => 'Transtornos Globais'],
            ['description' => 'Crônicas Degenerativa'],
            ['description' => 'Síndrome de Down'],
            ['description' => 'Múltipla'],
        ];

        DeficiencyTypes::insert($deficiencies);
    }
}
