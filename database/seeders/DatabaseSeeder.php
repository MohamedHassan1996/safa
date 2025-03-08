<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Charity\CharitySeeder;
use Database\Seeders\Roles\RolesAndPermissionsSeeder;
use Database\Seeders\User\UserSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            CharitySeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            //ParameterSeeder::class
        ]);
    }
}
