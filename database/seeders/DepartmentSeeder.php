<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Ingeniería en Sistemas Computacionales', 'code' => 'ISC'],
            ['name' => 'Ingeniería Industrial', 'code' => 'II'],
            ['name' => 'Contaduría Pública', 'code' => 'CP'],
            ['name' => 'Arquitectura', 'code' => 'ARQ'],
            ['name' => 'Ciencias Básicas', 'code' => 'CB'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
