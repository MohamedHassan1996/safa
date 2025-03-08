<?php

namespace Database\seeders\Charity;

use App\Enums\Chairty\ChairtyStatus;
use App\Models\Charity\Charity;
use Illuminate\Database\Seeder;


class CharitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = new Charity();
        $user->name = 'الصفا و المروة';
        $user->note = null;
        $user->is_active = ChairtyStatus::ACTIVE;
        $user->save();


    }
}
