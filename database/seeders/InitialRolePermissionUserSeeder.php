<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class InitialRolePermissionUserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(CreateNewUser $fortifyCreateNewUser)
    {
        // reset cached roles and permissions

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // initial permissions

        $permissions = [
            'ManageAministrators' => 'manage administrators',
            'ManageUsers' => 'manage users',
            'CreateTeams' => 'create teams'
        ];

        foreach($permissions as $var => $name) {
            ${'permission' . $var} = Permission::create(['name' => $name]);
        }

        // initial roles

        $roles = [
            'SuperAdmin' => 'Super-Administrator',
            'Admin' => 'Administrator',
            'Default' => 'Default',
            'Guest' => 'Guest'
        ];

        foreach($roles as $var => $name) {
            ${'role' . $var} = Role::create(['name' => $name]);
        }

        // assign permissions to roles

        $roleAdmin->syncPermissions([
            $permissionManageUsers,
            $permissionCreateTeams,
        ]);

        // initial users

        $fortifyCreateNewUser->createInitialUser('Super-Administrator', [
            'name' => env('INITIAL_USER_SUPERADMIN_NAME', false),
            'email'=> env('INITIAL_USER_SUPERADMIN_EMAIL', false),
            'password' => env('INITIAL_USER_SUPERADMIN_INSTALLATION_PASSWORD', false),
        ]);

    }

}
