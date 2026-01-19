<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\Thing;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(5)->create();

        Place::factory()->count(10)->create();

        Unit::factory()->count(5)->create();

        Thing::factory()->count(30)->create();
    }
}
