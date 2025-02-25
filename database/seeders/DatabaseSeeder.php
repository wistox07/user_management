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
        $userSystem = User::factory()->create([
            "username" => "system",
            "email" => "system@local.com",
            "phone_number" => null,
            "status" => 1,
            "password" => "system",
            'created_by' => null,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null,
        ]);

        $principalSystem = System::factory()->create([
            "name" => "Sistema de Gestión de Usuarios",
            "url" => "gestion_usuarios.com",
            "description" => "Sistema de Gestión de Usuarios",
            "status" => 1,
            "created_by" => $userSystem->id,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null,
        ]);

        $roles = Role::factory()->createMany([
        [
            "name" => "System",
            "description" => "System",
            "status" => 1,
            "created_by" => $userSystem->id,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null,
        ],[
            "name" => "Admin",
            "description" => "Administrator",
            "status" => 1,
            "created_by" => $userSystem->id,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null,
        ],[
            "name" => "User",
            "description" => "User",
            "status" => 1,
            "created_by" => $userSystem->id,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null,
        ]])->each(function ($role) use ($principalSystem, $userSystem){
            if($role->name !== "System"){
                SystemRole::factory()->create([
                    "role_id" => $role->id,
                    "system_id" => $principalSystem->id,
                    "status"  => 1,
                    "created_by" => $userSystem->id,
                    'updated_by' => null,
                    'deleted_by' => null,
                    'delete_at' => null,
                ]);
            }
        });

    
        $adminRole = $roles->firstWhere('name', 'Admin');



        $adminUser = User::factory()->create([
            "username" => "admin",
            "email" => "admin@local.com",
            "phone_number" => null,
            "status" => 1,
            "password" => Hash::make('admin'),
            'created_by' => null,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null,
        ]);
        /*
        Cuando se logea agrega datos de su perfil
        */
        UserSystemRole::factory()->create([
            "user_id" => $adminUser->id,
            "system_id" => $principalSystem->id,
            "role_id" => $adminRole->id,
            "status" => 1,
            "created_by" => $userSystem->id,
            'updated_by' => null,
            'deleted_by' => null,
            'delete_at' => null
        ]);


        
        User::factory(10)->create([
            'created_by' => $adminUser->id,
        ])->each(function ($user) use ($adminUser){
            Profile::factory()->create([
                "user_id" => $user->id,
                "created_by" => $adminUser->id
            ]);
        });
        
    }
}
