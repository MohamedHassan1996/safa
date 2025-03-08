<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // premissions
        $permissions = [
            'all_users',
            'create_user',
            'edit_user',
            'update_user',
            'delete_user',
            'change_user_status',

            'all_charities',
            'create_charity',
            'edit_charity',
            'update_charity',
            'delete_charity',

            'all_cases',
            'create_case',
            'edit_case',
            'update_case',
            'delete_case',

            'all_donations',
            'create_donation',
            'edit_donation',
            'update_donation',
            'delete_donation',

            'all_roles',
            'create_role',
            'edit_role',
            'update_role',
            'delete_role',

            'all_parameters',
            'create_parameter',
            'edit_parameter',
            'update_parameter',
            'delete_parameter',

        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], [
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }

        // roles
        $superAdmin = Role::create(['name' => 'مدير عام']);
        $superAdmin->givePermissionTo(Permission::get());

        $accountant = Role::create(['name' => 'مدير جمعية']);
        $accountant->givePermissionTo([
            'all_users',
            'create_user',
            'edit_user',
            'update_user',
            'delete_user',
            'change_user_status',

            'all_cases',
            'create_case',
            'edit_case',
            'update_case',
            'delete_case',

            'all_donations',
            'create_donation',
            'edit_donation',
            'update_donation',
            'delete_donation',

            'all_roles',
            'create_role',
            'edit_role',
            'update_role',
            'delete_role',

            'all_parameters',
            'create_parameter',
            'edit_parameter',
            'update_parameter',
            'delete_parameter',
        ]);

        $supervisor = Role::create(['name' => 'مشرف']);
        $supervisor->givePermissionTo([

            'all_cases',
            'create_case',
            'edit_case',
            'update_case',
            'delete_case',

            'all_donations',
            'create_donation',
            'edit_donation',
            'update_donation',
            'delete_donation',

        ]);
    }
}
