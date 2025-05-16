<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'api'],
            ['id' => (string) Str::uuid()]
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'User', 'guard_name' => 'api'],
            ['id' => (string) Str::uuid()]
        );

        $adminPermissions = [
            'create-user',
            'read-user',
            'update-user',
            'delete-user',
            'create-role',
            'read-role',
            'update-role',
            'delete-role'
        ];

        $adminPermissionObjects = [];
        foreach ($adminPermissions as $permissionName) {
            $adminPermissionObjects[] = Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'api'],
                ['id' => (string) Str::uuid()]
            );
        }

        $adminRole->syncPermissions($adminPermissionObjects);

        $readRolePermission = Permission::firstOrCreate(
            ['name' => 'read-role', 'guard_name' => 'api'],
            ['id' => (string) Str::uuid()]
        );
        $userRole->syncPermissions([$readRolePermission]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Admin',
                'password' => Hash::make('admin'),
            ]
        );
        $admin->assignRole($adminRole);

        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'User',
                'password' => Hash::make('user'),
            ]
        );


        $user->assignRole($userRole);
    }
}
