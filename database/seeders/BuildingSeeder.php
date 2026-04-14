<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = [
            ['name' => 'Edificio 700', 'code' => '700'],
            ['name' => 'Edificio 800', 'code' => '800'],
            ['name' => 'Centro de Cómputo', 'code' => 'CC'],
            ['name' => 'Laboratorios Pesados', 'code' => 'LP'],
        ];

        foreach ($buildings as $b) {
            Building::create($b);
        }
    }
}
