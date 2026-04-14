<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $isc = Department::where('code', 'ISC')->first();
        $cb = Department::where('code', 'CB')->first();

        $subjects = [
            // Semestre 1
            ['code' => 'ACC-0901', 'name' => 'Ética', 'semester' => 1, 'weekly_hours' => 4, 'dept' => $isc],
            ['code' => 'ACA-0907', 'name' => 'Taller de Ética', 'semester' => 1, 'weekly_hours' => 4, 'dept' => $cb],
            ['code' => 'AEF-1041', 'name' => 'Fundamentos de Programación', 'semester' => 1, 'weekly_hours' => 5, 'dept' => $isc],
            
            // Semestre 2
            ['code' => 'AED-1285', 'name' => 'Cálculo Diferencial', 'semester' => 2, 'weekly_hours' => 5, 'dept' => $cb],
            ['code' => 'SCD-1018', 'name' => 'Programación Orientada a Objetos', 'semester' => 2, 'weekly_hours' => 5, 'dept' => $isc],
            ['code' => 'AEC-1008', 'name' => 'Contabilidad Financiera', 'semester' => 2, 'weekly_hours' => 4, 'dept' => $isc],

            // Semestre 3
            ['code' => 'AEF-1052', 'name' => 'Estructura de Datos', 'semester' => 3, 'weekly_hours' => 5, 'dept' => $isc],
            ['code' => 'ACF-0903', 'name' => 'Álgebra Lineal', 'semester' => 3, 'weekly_hours' => 5, 'dept' => $cb],
            ['code' => 'SCD-1027', 'name' => 'Taller de Sistemas Operativos', 'semester' => 3, 'weekly_hours' => 4, 'dept' => $isc],

            // Semestre 4
            ['code' => 'AEF-1031', 'name' => 'Fundamentos de Bases de Datos', 'semester' => 4, 'weekly_hours' => 5, 'dept' => $isc],
            ['code' => 'SCD-1015', 'name' => 'Lenguajes y Autómatas I', 'semester' => 4, 'weekly_hours' => 5, 'dept' => $isc],
            ['code' => 'ACF-0904', 'name' => 'Cálculo Integral', 'semester' => 4, 'weekly_hours' => 5, 'dept' => $cb],
        ];

        foreach ($subjects as $s) {
            Subject::create([
                'code' => $s['code'],
                'name' => $s['name'],
                'semester' => $s['semester'],
                'weekly_hours' => $s['weekly_hours'],
                'department_id' => $s['dept']->id,
            ]);
        }
    }
}
