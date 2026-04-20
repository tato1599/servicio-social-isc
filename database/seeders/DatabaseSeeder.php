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
        // 1. Ejecutar seeders de infraestructura básica (primero departamentos)
        $this->call([
            DepartmentSeeder::class,
            BuildingSeeder::class,
            ClassroomSeeder::class,
        ]);

        // 2. Crear usuario administrador
        $this->createUserWithTeam([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_id' => null,
        ]);

        // 3. Crear coordinadores para diferentes departamentos
        $iscDept = \App\Models\Department::where('code', 'ISC')->first();
        $ieDept = \App\Models\Department::where('code', 'IE')->first();

        $this->createUserWithTeam([
            'name' => 'Jefe Sistemas',
            'email' => 'sistemas@example.com',
            'password' => Hash::make('password'),
            'role' => 'coordinador',
            'department_id' => $iscDept->id,
        ]);

        $this->createUserWithTeam([
            'name' => 'Jefe Electromecánica',
            'email' => 'electromecanica@example.com',
            'password' => Hash::make('password'),
            'role' => 'coordinador',
            'department_id' => $ieDept->id,
        ]);

        $this->command->info("Usuarios de prueba creados: admin@example.com, sistemas@example.com, electromecanica@example.com (password: password)");
        $this->command->info("Sistema limpio y listo para importación real de datos.");
    }

    /**
     * Crea un usuario y le asigna un equipo personal (Requerido por Jetstream)
     */
    protected function createUserWithTeam(array $userData): User
    {
        $user = User::updateOrCreate(['email' => $userData['email']], $userData);

        if ($user->ownedTeams()->count() === 0) {
            $team = \App\Models\Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0] . "'s Team",
                'personal_team' => true,
            ]);

            $user->current_team_id = $team->id;
            $user->save();
        }

        return $user;
    }
}
