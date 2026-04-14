<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $isc = Department::firstOrCreate(['code' => 'ISC'], ['name' => 'Ingeniería en Sistemas']);
        $cb = Department::firstOrCreate(['code' => 'CB'], ['name' => 'Ciencias Básicas']);

        $teachers = [
            // Maestros de Base (Alta Prioridad)
            ['name' => 'Dr. Juan Carlos Pérez', 'priority' => 10, 'type' => 'base', 'min' => 20, 'max' => 40, 'dept' => $isc],
            ['name' => 'Mtra. María Elena García', 'priority' => 9, 'type' => 'base', 'min' => 20, 'max' => 40, 'dept' => $isc],
            ['name' => 'Dr. Roberto Sánchez Gómez', 'priority' => 10, 'type' => 'base', 'min' => 20, 'max' => 40, 'dept' => $cb],
            ['name' => 'Mtra. Ana Patricia Torres', 'priority' => 8, 'type' => 'base', 'min' => 0, 'max' => 40, 'dept' => $isc],
            
            // Maestros por Honorarios (Baja Prioridad)
            ['name' => 'Ing. Luis Fernando Díaz', 'priority' => 5, 'type' => 'honorarios', 'min' => 0, 'max' => 20, 'dept' => $isc],
            ['name' => 'Ing. Claudia Isabel Ruiz', 'priority' => 4, 'type' => 'honorarios', 'min' => 0, 'max' => 15, 'dept' => $isc],
            ['name' => 'Lic. Sergio Valenzuela', 'priority' => 3, 'type' => 'honorarios', 'min' => 0, 'max' => 10, 'dept' => $cb],
        ];

        foreach ($teachers as $t) {
            Teacher::create([
                'name' => $t['name'],
                'employee_id' => 'EMP-' . rand(1000, 9999),
                'priority' => $t['priority'],
                'type' => $t['type'],
                'min_hours' => $t['min'],
                'max_hours' => $t['max'],
                'department_id' => $t['dept']->id,
            ]);
        }
    }
}
