<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\System;
use App\Models\Role;
use App\Models\SystemRole;
use App\Models\UserSystemRole;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        return [
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'status' => rand(0,1),
            'password' => Hash::make('1234578'),
            'created_by' => null,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null,
        ];

        */

        $principalSystem = System::factory()->create([
            "name" => "sistema de gestión de usuarios",
            "url" => "gestion_usuarios.com",
            "short_name" => "gestión de usuarios",
            "identifier_code" => "sistema_gestion_usuarios",
            "description" => "sistema de gestión de usuarios",
            "status" => 1,
            "created_by" => null,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null,
        ]);

        $roles = Role::factory()->createMany([
            [
                "name" => "sistema",
                "description" => "sistema",
                "short_name" => "sistema",
                "identifier_code" => "rol_sistema",
                "status" => 1,
                "created_by" => null,
                'updated_by' => null,
                'deleted_by' => null,
                'delete_at' => null,
            ],
            [
                "name" => "administrador general",
                "description" => "administrador general",
                "short_name" => "administrador",
                "identifier_code" => "rol_administrador",
                "status" => 1,
                "created_by" => null,
                'updated_by' => null,
                'deleted_by' => null,
                'delete_at' => null,
            ],
            [
                "name" => "usuario",
                "description" => "usuario",
                "short_name" => "usuario",
                "identifier_code" => "rol_usuario",
                "status" => 1,
                "created_by" => null,
                'updated_by' => null,
                'deleted_by' => null,
                'delete_at' => null,
            ]
        ])->each(function ($role) use ($principalSystem) {
            SystemRole::factory()->create([
                "role_id" => $role->id,
                "system_id" => $principalSystem->id,
                "status"  => 1,
                "created_by" => null,
                'updated_by' => null,
                'deleted_by' => null,
                'delete_at' => null,
            ]);
        });

        $systemRole = $roles->firstWhere('identifier_code', 'rol_sistema');
        $adminRole = $roles->firstWhere('identifier_code', 'rol_administrador');
        $userRole = $roles->firstWhere('identifier_code', 'rol_usuario');


        $principalUsers = User::factory()->createMany([
            [
                "username" => "sistema",
                "email" => "system@local.com",
                "phone_number" => null,
                "status" => 1,
                "password" => "system",
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'delete_at' => null,
            ],
            [
                "username" => "administrador",
                "email" => "admin@local.com",
                "phone_number" => null,
                "status" => 1,
                "password" => Hash::make('admin'),
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
                'delete_at' => null,
            ]
        ])->each(function ($user) use ($principalSystem, $adminRole, $systemRole) {

            UserSystemRole::factory()->create([
                "user_id" => $user->id,
                "system_id" => $principalSystem->id,
                "role_id" => $user->username == "sistema" ? $systemRole : $adminRole,
                "status" => 1,
                "created_by" => null,
                'updated_by' => null,
                'deleted_by' => null,
                'delete_at' => null
            ]);
        });

        $userSystemRoleAdministrator = UserSystemRole::where("role_id", $adminRole->id)->first();


        /*
        Cuando adminsitrador ingresa con su usuario , debe llenar los datos de su perfil
        Cuando se cree un nuevo sistema , se va a agregar por defecto accesos a los usuarios admin y system para que puedan acceder
        */




        User::factory(10)->create([
            'created_by' => $userSystemRoleAdministrator->id,
        ])->each(function ($user) use ($userSystemRoleAdministrator, $principalSystem, $userRole) {
            Profile::factory()->create([
                "user_id" => $user->id,
                "created_by" => $userSystemRoleAdministrator->id
            ]);
            UserSystemRole::factory()->create([
                "user_id" => $user->id,
                "system_id" => $principalSystem->id,
                "role_id" => $userRole->id,
                "status" => 1,
                "created_by" => $userSystemRoleAdministrator->id,
                'updated_by' => null,
                'deleted_by' => null,
                'delete_at' => null
            ]);
        });
    }
}
