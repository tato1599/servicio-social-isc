<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = Building::all();

        foreach ($buildings as $b) {
            if ($b->code === '700' || $b->code === '800') {
                // Crear 5 salones por edificio
                for ($i = 1; $i <= 5; $i++) {
                    Classroom::updateOrCreate(
                        ['name' => "Salón " . $b->code . "-" . $i, 'building_id' => $b->id],
                        ['capacity' => 40]
                    );
                }
            } elseif ($b->code === 'CC') {
                Classroom::updateOrCreate(['name' => 'Lab Cómputo A', 'building_id' => $b->id], ['capacity' => 30]);
                Classroom::updateOrCreate(['name' => 'Lab Cómputo B', 'building_id' => $b->id], ['capacity' => 30]);
                Classroom::updateOrCreate(['name' => 'Lab Redes', 'building_id' => $b->id], ['capacity' => 20]);
            } elseif ($b->code === 'LP') {
                Classroom::updateOrCreate(['name' => 'Lab Química', 'building_id' => $b->id], ['capacity' => 25]);
                Classroom::updateOrCreate(['name' => 'Taller Electrónica', 'building_id' => $b->id], ['capacity' => 25]);
            }
        }
    }
}
