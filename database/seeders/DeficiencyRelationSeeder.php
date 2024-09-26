<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeficiencyRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // tipo de deficiência 1
            ['deficiency_id' => 1,'deficiency_types_id' => 1],
            ['deficiency_id' => 2,'deficiency_types_id' => 1],
            ['deficiency_id' => 3,'deficiency_types_id' => 1],
            ['deficiency_id' => 4,'deficiency_types_id' => 1],
            ['deficiency_id' => 5,'deficiency_types_id' => 1],
            ['deficiency_id' => 6,'deficiency_types_id' => 1],
            ['deficiency_id' => 7,'deficiency_types_id' => 1],
            ['deficiency_id' => 8,'deficiency_types_id' => 1],
            ['deficiency_id' => 9,'deficiency_types_id' => 1],
            ['deficiency_id' => 10,'deficiency_types_id' => 1],
            ['deficiency_id' => 11,'deficiency_types_id' => 1],
            
            // tipo de deficiência 2
            ['deficiency_id' => 1,'deficiency_types_id' => 2],
            ['deficiency_id' => 12,'deficiency_types_id' => 2],
            ['deficiency_id' => 13,'deficiency_types_id' => 2],
            ['deficiency_id' => 14,'deficiency_types_id' => 2],
            ['deficiency_id' => 15,'deficiency_types_id' => 2],
            ['deficiency_id' => 16,'deficiency_types_id' => 2],
            
            // tipo de deficiência 3
            ['deficiency_id' => 1,'deficiency_types_id' => 3],
            ['deficiency_id' => 17,'deficiency_types_id' => 3],
            ['deficiency_id' => 18,'deficiency_types_id' => 3],
            ['deficiency_id' => 19,'deficiency_types_id' => 3],
            ['deficiency_id' => 20,'deficiency_types_id' => 3],
            
            // tipo de deficiência 4
            ['deficiency_id' => 1,'deficiency_types_id' => 4],
            ['deficiency_id' => 21,'deficiency_types_id' => 4],
            ['deficiency_id' => 22,'deficiency_types_id' => 4],
            ['deficiency_id' => 23,'deficiency_types_id' => 4],
            ['deficiency_id' => 24,'deficiency_types_id' => 4],
            ['deficiency_id' => 36,'deficiency_types_id' => 4],
            
            // tipo de deficiência 5
            ['deficiency_id' => 1,'deficiency_types_id' => 5],
            ['deficiency_id' => 12,'deficiency_types_id' => 5],
            ['deficiency_id' => 13,'deficiency_types_id' => 5],
            ['deficiency_id' => 14,'deficiency_types_id' => 5],
            ['deficiency_id' => 15,'deficiency_types_id' => 5],
            ['deficiency_id' => 16,'deficiency_types_id' => 5],
            ['deficiency_id' => 17,'deficiency_types_id' => 5],
            ['deficiency_id' => 18,'deficiency_types_id' => 5],
            ['deficiency_id' => 19,'deficiency_types_id' => 5],
            ['deficiency_id' => 20,'deficiency_types_id' => 5],

            // tipo de deficiência 6
            ['deficiency_id' => 1,'deficiency_types_id' => 6],
            ['deficiency_id' => 25,'deficiency_types_id' => 6],
            ['deficiency_id' => 26,'deficiency_types_id' => 6],
            ['deficiency_id' => 27,'deficiency_types_id' => 6],
            ['deficiency_id' => 28,'deficiency_types_id' => 6],
            ['deficiency_id' => 29,'deficiency_types_id' => 6],
            
            // tipo de deficiência 7
            ['deficiency_id' => 1,'deficiency_types_id' => 7],
            ['deficiency_id' => 30,'deficiency_types_id' => 7],
            ['deficiency_id' => 31,'deficiency_types_id' => 7],
            ['deficiency_id' => 32,'deficiency_types_id' => 7],
            ['deficiency_id' => 33,'deficiency_types_id' => 7],
            ['deficiency_id' => 34,'deficiency_types_id' => 7],
            ['deficiency_id' => 35,'deficiency_types_id' => 7],
            
            // tipo de deficiência 9 (Todos de 1 a 36)
            ['deficiency_id' => 1,'deficiency_types_id' => 9],
            ['deficiency_id' => 2,'deficiency_types_id' => 9],
            ['deficiency_id' => 3,'deficiency_types_id' => 9],
            ['deficiency_id' => 4,'deficiency_types_id' => 9],
            ['deficiency_id' => 5,'deficiency_types_id' => 9],
            ['deficiency_id' => 6,'deficiency_types_id' => 9],
            ['deficiency_id' => 7,'deficiency_types_id' => 9],
            ['deficiency_id' => 8,'deficiency_types_id' => 9],
            ['deficiency_id' => 9,'deficiency_types_id' => 9],
            ['deficiency_id' => 10,'deficiency_types_id' => 9],
            ['deficiency_id' => 11,'deficiency_types_id' => 9],
            ['deficiency_id' => 12,'deficiency_types_id' => 9],
            ['deficiency_id' => 13,'deficiency_types_id' => 9],
            ['deficiency_id' => 14,'deficiency_types_id' => 9],
            ['deficiency_id' => 15,'deficiency_types_id' => 9],
            ['deficiency_id' => 16,'deficiency_types_id' => 9],
            ['deficiency_id' => 17,'deficiency_types_id' => 9],
            ['deficiency_id' => 18,'deficiency_types_id' => 9],
            ['deficiency_id' => 19,'deficiency_types_id' => 9],
            ['deficiency_id' => 20,'deficiency_types_id' => 9],
            ['deficiency_id' => 21,'deficiency_types_id' => 9],
            ['deficiency_id' => 22,'deficiency_types_id' => 9],
            ['deficiency_id' => 23,'deficiency_types_id' => 9],
            ['deficiency_id' => 24,'deficiency_types_id' => 9],
            ['deficiency_id' => 25,'deficiency_types_id' => 9],
            ['deficiency_id' => 26,'deficiency_types_id' => 9],
            ['deficiency_id' => 27,'deficiency_types_id' => 9],
            ['deficiency_id' => 28,'deficiency_types_id' => 9],
            ['deficiency_id' => 29,'deficiency_types_id' => 9],
            ['deficiency_id' => 30,'deficiency_types_id' => 9],
            ['deficiency_id' => 31,'deficiency_types_id' => 9],
            ['deficiency_id' => 32,'deficiency_types_id' => 9],
            ['deficiency_id' => 33,'deficiency_types_id' => 9],
            ['deficiency_id' => 34,'deficiency_types_id' => 9],
            ['deficiency_id' => 35,'deficiency_types_id' => 9],
            ['deficiency_id' => 36,'deficiency_types_id' => 9],
        ];

        DB::table('deficiency_to_deficiency_types')->insert($data);
    }
}
