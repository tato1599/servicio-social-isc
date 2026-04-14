<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\ScheduleService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear usuario administrador
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Admin User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // Asegurar que tenga un equipo (Jetstream requiere uno si Teams está activo)
        if ($user->ownedTeams()->count() === 0) {
            $team = \App\Models\Team::forceCreate([
                'user_id' => $user->id,
                'name' => 'Equipo Administrativo',
                'personal_team' => true,
            ]);
            $user->current_team_id = $team->id;
            $user->save();
        }

        // 2. Ejecutar seeders de entidades
        $this->call([
            DepartmentSeeder::class,
            BuildingSeeder::class,
            ClassroomSeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            CourseSeeder::class,
        ]);

        // 3. Ejecutar Generación Automática de Horarios
        $scheduleService = new ScheduleService();
        $totalAssigned = $scheduleService->generateAutomaticSchedules();

        $this->command->info("Se han asignado automáticamente {$totalAssigned} espacios de horario.");
    }
}
