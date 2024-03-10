<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deficiency;

class DeficiencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deficiencies = [
            ['description' => 'Outros'],
            ['description' => 'Paralisia Total [Tetraplegia]'],
            ['description' => 'Fraqueza Total [Tetraparesia]'],
            ['description' => 'Paralisia um Membro [Monoplegia]'],
            ['description' => 'Limitação um Membro [Monoparesia]'],
            ['description' => 'Paralisia Metade Corpo [Hemiplegia]'],
            ['description' => 'Limitação Metade Corpo [Hemiparesia]'],
            ['description' => 'Baixa Estatura [Nanismo]'],
            ['description' => 'Paralisia Cerebral'],
            ['description' => 'Ausência Membros'],
            ['description' => 'Ostomia'],
            ['description' => 'Baixa Visão Leve [Acuidade Visual -> 0,80 a 0,30]'],
            ['description' => 'Baixa Visão Moderada [Acuidade Visual -> 0,30 a 0,12]'],
            ['description' => 'Baixa Visão Grave [Acuidade Visual -> 0,12 a 0,05]'],
            ['description' => 'Cegueira Parcial [Acuidade Visual -> 0,05 a 0,01]'],
            ['description' => 'Cegueira Total [Acuidade Visual -> 0]'],
            ['description' => 'Perda Auditiva Leve [26 a 40 dB]'],
            ['description' => 'Perda Auditiva Moderada [41 a 60 dB]'],
            ['description' => 'Perda Auditiva Severa [61 a 80 dB]'],
            ['description' => 'Perda Auditiva Profunda [81 maior dB]'],
            ['description' => 'Retardo Intelectual Leve [QI entre 50 - 69]'],
            ['description' => 'Retardo Intelectual Moderado [QI entre 35 - 49]'],
            ['description' => 'Retardo Intelectual Grave [QI entre 20 - 34]'],
            ['description' => 'Retardo Intelectual Profundo [QI menor que 20]'],
            ['description' => 'Autismo'],
            ['description' => 'Psicose Infantil'],
            ['description' => 'Síndrome de Kanner'],
            ['description' => 'Síndrome de Rett'],
            ['description' => 'Síndrome de Asperger'],
            ['description' => 'Sedentarismo'],
            ['description' => 'Fumante'],
            ['description' => 'Obesidade'],
            ['description' => 'Câncer'],
            ['description' => 'Hemofilia'],
            ['description' => 'Diabete'],
        ];

        Deficiency::insert($deficiencies);
    }
}
