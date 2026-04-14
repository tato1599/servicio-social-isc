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
                    Classroom::create([
                        'name' => "Salón " . $b->code . "-" . $i,
                        'capacity' => 40,
                        'building_id' => $b->id,
                    ]);
                }
            } elseif ($b->code === 'CC') {
                Classroom::create(['name' => 'Lab Cómputo A', 'capacity' => 30, 'building_id' => $b->id]);
                Classroom::create(['name' => 'Lab Cómputo B', 'capacity' => 30, 'building_id' => $b->id]);
                Classroom::create(['name' => 'Lab Redes', 'capacity' => 20, 'building_id' => $b->id]);
            } elseif ($b->code === 'LP') {
                Classroom::create(['name' => 'Lab Química', 'capacity' => 25, 'building_id' => $b->id]);
                Classroom::create(['name' => 'Taller Electrónica', 'capacity' => 25, 'building_id' => $b->id]);
            }
        }
    }
}
