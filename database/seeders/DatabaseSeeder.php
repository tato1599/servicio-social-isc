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

        // 2. Ejecutar seeders de infraestructura básica
        $this->call([
                // DepartmentSeeder::class,
            BuildingSeeder::class,
            ClassroomSeeder::class,
            // SubjectSeeder::class,
            // TeacherSeeder::class,
            // CourseSeeder::class,
        ]);

        $this->command->info("Sistema limpio y listo para importación real de datos.");
    }
}
