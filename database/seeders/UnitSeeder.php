<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'штуки', 'short' => 'шт.'],
            ['name' => 'килограммы', 'short' => 'кг'],
            ['name' => 'литры', 'short' => 'л'],
            ['name' => 'метры', 'short' => 'м'],
            ['name' => 'пачки', 'short' => 'пач.'],
            ['name' => 'граммы', 'short' => 'г'],
            ['name' => 'миллилитры', 'short' => 'мл'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate($unit);
        }
    }
}
