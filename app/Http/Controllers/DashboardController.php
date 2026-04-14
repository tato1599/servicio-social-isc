<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_teachers' => Teacher::count(),
            'occupied_classrooms' => \App\Models\Schedule::distinct('classroom_id')->count(),
            'schedule_conflicts' => 0, // Simplified for now
            'active_social_service' => 25, // Mocked for now
        ];

        return view('dashboard', compact('stats'));
    }

    public function generate(Request $request, \App\Services\ScheduleService $service)
    {
        $count = $service->generateAutomaticSchedules();
        
        return back()->with('message', "¡Éxito! Se han generado {$count} asignaciones de horario automáticamente.");
    }

    public function generateFromRequirements(Request $request, \App\Services\ScheduleService $service)
    {
        // 1. Ejecutar el seeder para cargar los requerimientos (o importar Excel)
        $seeder = new \Database\Seeders\RequirementSeeder();
        $seeder->run();

        // 2. Generar horarios basados en esos requerimientos
        $count = $service->generateFromRequirements('Aug-Dec 2024');
        
        return back()->with('message', "¡Éxito! Se han importado y generado {$count} asignaciones basadas en el requerimiento de Excel.");
    }
}
