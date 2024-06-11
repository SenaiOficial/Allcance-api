<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeficienciesSeeder extends Seeder
{
    public function run()
    {
        $deficiencyTypes = DB::table('deficiency_types')->get()->keyBy('description');

        $deficiencies = [
            ['description' => 'Outros', 'deficiency_types_id' => $deficiencyTypes['Crônicas Degenerativa']->id],
            ['description' => 'Paralisia Total [Tetraplegia]', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Fraqueza Total [Tetraparesia]', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Paralisia um Membro [Monoplegia]', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Limitação um Membro [Monoparesia]', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Paralisia Metade Corpo [Hemiplegia]', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Limitação Metade Corpo [Hemiparesia]', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Baixa Estatura [Nanismo]', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Paralisia Cerebral', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Ausência Membros', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Ostomia', 'deficiency_types_id' => $deficiencyTypes['Física']->id],
            ['description' => 'Baixa Visão Leve [Acuidade Visual -> 0,80 a 0,30]', 'deficiency_types_id' => $deficiencyTypes['Visual']->id],
            ['description' => 'Baixa Visão Moderada [Acuidade Visual -> 0,30 a 0,12]', 'deficiency_types_id' => $deficiencyTypes['Visual']->id],
            ['description' => 'Baixa Visão Grave [Acuidade Visual -> 0,12 a 0,05]', 'deficiency_types_id' => $deficiencyTypes['Visual']->id],
            ['description' => 'Cegueira Parcial [Acuidade Visual -> 0,05 a 0,01]', 'deficiency_types_id' => $deficiencyTypes['Visual']->id],
            ['description' => 'Cegueira Total [Acuidade Visual -> 0]', 'deficiency_types_id' => $deficiencyTypes['Visual']->id],
            ['description' => 'Perda Auditiva Leve [26 a 40 dB]', 'deficiency_types_id' => $deficiencyTypes['Auditiva']->id],
            ['description' => 'Perda Auditiva Moderada [41 a 60 dB]', 'deficiency_types_id' => $deficiencyTypes['Auditiva']->id],
            ['description' => 'Perda Auditiva Severa [61 a 80 dB]', 'deficiency_types_id' => $deficiencyTypes['Auditiva']->id],
            ['description' => 'Perda Auditiva Profunda [81 maior dB]', 'deficiency_types_id' => $deficiencyTypes['Auditiva']->id],
            ['description' => 'Retardo Intelectual Leve [QI entre 50 - 69]', 'deficiency_types_id' => $deficiencyTypes['Intelectual']->id],
            ['description' => 'Retardo Intelectual Moderado [QI entre 35 - 49]', 'deficiency_types_id' => $deficiencyTypes['Intelectual']->id],
            ['description' => 'Retardo Intelectual Grave [QI entre 20 - 34]', 'deficiency_types_id' => $deficiencyTypes['Intelectual']->id],
            ['description' => 'Retardo Intelectual Profundo [QI menor que 20]', 'deficiency_types_id' => $deficiencyTypes['Intelectual']->id],
            ['description' => 'Autismo', 'deficiency_types_id' => $deficiencyTypes['Transtornos Globais']->id],
            ['description' => 'Psicose Infantil', 'deficiency_types_id' => $deficiencyTypes['Transtornos Globais']->id],
            ['description' => 'Síndrome de Kanner', 'deficiency_types_id' => $deficiencyTypes['Transtornos Globais']->id],
            ['description' => 'Síndrome de Rett', 'deficiency_types_id' => $deficiencyTypes['Transtornos Globais']->id],
            ['description' => 'Síndrome de Asperger', 'deficiency_types_id' => $deficiencyTypes['Transtornos Globais']->id],
            ['description' => 'Sedentarismo', 'deficiency_types_id' => $deficiencyTypes['Crônicas Degenerativa']->id],
            ['description' => 'Fumante', 'deficiency_types_id' => $deficiencyTypes['Crônicas Degenerativa']->id],
            ['description' => 'Obesidade', 'deficiency_types_id' => $deficiencyTypes['Crônicas Degenerativa']->id],
            ['description' => 'Câncer', 'deficiency_types_id' => $deficiencyTypes['Crônicas Degenerativa']->id],
            ['description' => 'Hemofilia', 'deficiency_types_id' => $deficiencyTypes['Crônicas Degenerativa']->id],
            ['description' => 'Diabete', 'deficiency_types_id' => $deficiencyTypes['Crônicas Degenerativa']->id],
        ];

        // Insere as deficiências no banco de dados
        DB::table('deficiencies')->insert($deficiencies);
    }
}
