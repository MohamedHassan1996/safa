<?php

namespace Database\Seeders\User;

use App\Enums\User\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->command->info('Creating Admin User...');

        try {

            $user = new User();
            $user->username = 'admin';
            $user->name = 'مستر محمد عبده';
            $user->email = 'admin@admin.com';
            $user->password = 'mody01002361528';
            $user->status = UserStatus::ACTIVE;
            $user->email_verified_at = now();
            $user->phone = '1234567890';
            $user->address = 'Admin Address';
            $user->charity_id = 1;
            $user->save();

            $this->command->info('User created successfully.');


            $this->command->info('Checking for "superAdmin" role...');
            $role = Role::where('name', 'مدير عام')->first();

            $user->assignRole($role);

        } catch (\Exception $e) {
            $this->command->error('Error creating user: ' . $e->getMessage());
            return;
        }

    }
}
