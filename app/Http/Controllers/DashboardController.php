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
}
