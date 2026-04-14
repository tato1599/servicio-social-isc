<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Building;
use App\Models\Classroom;
use App\Models\Course;

class RequirementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Asegurar Departamentos
        $deptISC = Department::firstOrCreate(['name' => 'Ingeniería en Sistemas Computacionales'], ['code' => 'ISC']);
        $deptTICS = Department::firstOrCreate(['name' => 'Ingeniería en Tecnologías de la Información'], ['code' => 'TICS']);
        $deptIND = Department::firstOrCreate(['name' => 'Ingeniería Industrial'], ['code' => 'IND']);
        $deptADM = Department::firstOrCreate(['name' => 'Licenciatura en Administración'], ['code' => 'ADM']);

        // 2. Asegurar Edificios y Salones
        $building = Building::firstOrCreate(['name' => 'Edificio Central', 'code' => 'EC']);
        
        $rooms = ['701', '702', '703', '704', '705', '706', '707', '125', '136', '134', '132', '135', '238', '404', '106'];
        foreach ($rooms as $room) {
            Classroom::firstOrCreate(['name' => "Salón $room", 'building_id' => $building->id], ['capacity' => 40]);
        }

        // 3. Requerimientos extraídos del Excel (Muestra representativa)
        $requirements = [
            // ISC SEM 1
            ['carrier' => 'ISC', 'sem' => 1, 'subj' => 'FUNDAMENTOS DE PROGRAMACIÓN', 'code' => '2EA', 'hours' => 5, 'teacher' => 'LUDI TORAL', 'slot' => 'B,E,G', 'room' => '701'],
            ['carrier' => 'ISC', 'sem' => 1, 'subj' => 'TALLER DE ÉTICA', 'code' => '3EA', 'hours' => 4, 'teacher' => 'REFUGIO GONZALEZ', 'slot' => 'B', 'room' => '106'],
            ['carrier' => 'ISC', 'sem' => 1, 'subj' => 'FUNDAMENTOS DE INVESTIGACIÓN', 'code' => '6EA', 'hours' => 4, 'teacher' => 'ROSI CASTAÑEDA', 'slot' => 'B', 'room' => '404'],
            
            // ISC SEM 2
            ['carrier' => 'ISC', 'sem' => 2, 'subj' => 'PROGRAMACIÓN ORIENTADA A OBJETOS', 'code' => '2EB', 'hours' => 5, 'teacher' => 'VERONICA FARIAS', 'slot' => 'D', 'room' => '705'],
            ['carrier' => 'ISC', 'sem' => 2, 'subj' => 'PROGRAMACIÓN ORIENTADA A OBJETOS', 'code' => '2EB', 'hours' => 5, 'teacher' => 'MARTHA CASTAÑEDA', 'slot' => 'G', 'room' => '705'],
            
            // ISC SEM 3
            ['carrier' => 'ISC', 'sem' => 3, 'subj' => 'ESTRUCTURA DE DATOS', 'code' => '2EC', 'hours' => 5, 'teacher' => 'MARU SANCHEZ', 'slot' => 'B', 'room' => '705'],
            ['carrier' => 'ISC', 'sem' => 3, 'subj' => 'SISTEMAS OPERATIVOS', 'code' => '5EC', 'hours' => 4, 'teacher' => 'PATY GALLEGOS', 'slot' => 'C,F', 'room' => '703'],
            
            // TICS SEM 1
            ['carrier' => 'TICS', 'sem' => 1, 'subj' => 'FUNDAMENTOS DE REDES', 'code' => '1JE', 'hours' => 5, 'teacher' => 'HILARIO PARTIDA', 'slot' => 'F', 'room' => '707'],
            ['carrier' => 'TICS', 'sem' => 1, 'subj' => 'TELECOMUNICACIONES', 'code' => '2JE', 'hours' => 5, 'teacher' => 'HILARIO PARTIDA', 'slot' => 'G', 'room' => '132'],
        ];

        foreach ($requirements as $req) {
            $dept = $req['carrier'] === 'ISC' ? $deptISC : $deptTICS;
            
            // Asegurar Materia
            $subject = Subject::firstOrCreate(
                ['code' => $req['code']],
                ['name' => $req['subj'], 'semester' => $req['sem'], 'weekly_hours' => $req['hours'], 'department_id' => $dept->id]
            );

            // Asegurar Maestro
            $teacher = Teacher::firstOrCreate(
                ['name' => $req['teacher']],
                ['employee_id' => 'EMP-' . rand(1000, 9999), 'department_id' => $dept->id, 'type' => 'base', 'priority' => 10]
            );

            // Asegurar Salón
            $classroom = Classroom::where('name', 'like', "%{$req['room']}")->first();

            // Crear Curso con Requerimientos
            Course::updateOrCreate(
                ['subject_id' => $subject->id, 'teacher_id' => $teacher->id, 'period' => 'Aug-Dec 2024'],
                [
                    'group_code' => 'A', 
                    'requirement_slot' => $req['slot'], 
                    'requirement_classroom_id' => $classroom?->id
                ]
            );
        }
    }
}
