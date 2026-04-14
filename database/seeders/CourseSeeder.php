<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $classrooms = Classroom::all();
        $slots = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

        // Asignación con métricas de carga para pruebas
        foreach ($subjects as $index => $subject) {
            $teacher = $teachers[$index % $teachers->count()];
            
            Course::create([
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'group_code' => 'A',
                'period' => 'Aug-Dec 2024',
                'study_plan' => 'ISC 2024',
                'students_count' => rand(20, 40),
                'students_count_adjusted' => rand(15, 35),
                'groups_count' => 1,
                'requirement_slot' => $slots[array_rand($slots)],
                'requirement_classroom_id' => $classrooms->random()->id,
            ]);

            // Duplicar algunos para ver carga pesada
            if ($index % 5 === 0) {
                Course::create([
                    'subject_id' => $subject->id,
                    'teacher_id' => $teachers[($index + 2) % $teachers->count()]->id,
                    'group_code' => 'B',
                    'period' => 'Aug-Dec 2024',
                    'study_plan' => 'ISC 2024',
                    'students_count' => rand(20, 30),
                    'students_count_adjusted' => rand(15, 25),
                    'groups_count' => 1,
                    'requirement_slot' => $slots[array_rand($slots)],
                    'requirement_classroom_id' => $classrooms->random()->id,
                ]);
            }
        }
    }
}
