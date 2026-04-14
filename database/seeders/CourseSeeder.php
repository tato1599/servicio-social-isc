<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = Subject::all();
        $teachers = Teacher::all();

        // Asignación simple para pruebas
        foreach ($subjects as $index => $subject) {
            // Rotar maestros
            $teacher = $teachers[$index % $teachers->count()];
            
            Course::create([
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'group_code' => 'A',
                'period' => 'Aug-Dec 2026',
            ]);

            // Crear un segundo grupo para algunas materias
            if ($index % 3 === 0) {
                $teacher2 = $teachers[($index + 1) % $teachers->count()];
                Course::create([
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher2->id,
                    'group_code' => 'B',
                    'period' => 'Aug-Dec 2026',
                ]);
            }
        }
    }
}
