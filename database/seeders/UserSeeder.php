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
            'delete-role',
        ];

        $sharedPermissions = [
            'read-role',

            'create-task',
            'read-task',
            'update-task',
            'delete-task',

            'create-interviewee',
            'read-interviewee',
            'update-interviewee',
            'delete-interviewee',

            'create-intervieweeTask',
            'read-intervieweeTask',
            'update-intervieweeTask',
            'delete-intervieweeTask',
        ];

        $adminPermissionObjects = [];
        foreach ($adminPermissions as $permissionName) {
            $adminPermissionObjects[] = Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'api'],
                ['id' => (string) Str::uuid()]
            );
        }

        $sharedPermissionObjects = [];
        foreach ($sharedPermissions as $permissionName) {
            $sharedPermissionObjects[] = Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'api'],
                ['id' => (string) Str::uuid()]
            );
        }

        $adminRole->syncPermissions([$adminPermissionObjects, $sharedPermissionObjects]);
        $userRole->syncPermissions($sharedPermissionObjects);

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
