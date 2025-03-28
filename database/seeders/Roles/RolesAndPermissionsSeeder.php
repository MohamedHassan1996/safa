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
            'destroy_user',
            'change_user_status',

            'all_charities',
            'create_charity',
            'edit_charity',
            'update_charity',
            'destroy_charity',

            'all_charity_cases',
            'create_charity_case',
            'edit_charity_case',
            'update_charity_case',
            'destroy_charity_case',
            'all_charity_case_logs',

            'all_charity_case_documents',
            'create_charity_case_document',
            'edit_charity_case_document',
            'update_charity_case_document',
            'destroy_charity_case_document',

            'all_donations',
            'create_donation',
            'edit_donation',
            'update_donation',
            'destroy_donation',
            'all_donation_logs',

            'all_roles',
            'create_role',
            'edit_role',
            'update_role',
            'destroy_role',

            'all_parameters',
            'create_parameter',
            'edit_parameter',
            'update_parameter',
            'destroy_parameter',

            'all_stats'

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
            'destroy_user',
            'change_user_status',

            'all_charity_cases',
            'create_charity_case',
            'edit_charity_case',
            'update_charity_case',
            'destroy_charity_case',

            'all_charity_case_documents',
            'create_charity_case_document',
            'edit_charity_case_document',
            'update_charity_case_document',
            'destroy_charity_case_document',

            'all_donations',
            'create_donation',
            'edit_donation',
            'update_donation',
            'destroy_donation',

            'all_roles',
            'create_role',
            'edit_role',
            'update_role',
            'destroy_role',

            'all_parameters',
            'create_parameter',
            'edit_parameter',
            'update_parameter',
            'destroy_parameter',

            'all_stats'
        ]);

        $supervisor = Role::create(['name' => 'مشرف']);
        $supervisor->givePermissionTo([

            'all_charity_cases',
            'create_charity_case',
            'edit_charity_case',
            'update_charity_case',
            'destroy_charity_case',

            'all_charity_case_documents',
            'create_charity_case_document',
            'edit_charity_case_document',
            'update_charity_case_document',
            'destroy_charity_case_document',

            'all_donations',
            'create_donation',
            'edit_donation',
            'update_donation',
            'destroy_donation',

        ]);
    }
}
