<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Thing;
use App\Models\Place;
use App\Models\Usage;
use Illuminate\Database\Seeder;

class FillMyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Твой ID — замени на реальный (узнай в tinker: auth()->user()->id)
        $myId = 1; // ← ВАЖНО! ПОМЕНЯЙ НА СВОЙ ID

        // 5 мест хранения для тебя
        $places = Place::factory()->count(5)->create(['owner_id' => $myId]);

        // 2 места в ремонте, 3 в работе
        $places[0]->update(['repair' => true]);
        $places[1]->update(['repair' => true]);

        // 15 вещей для тебя
        for ($i = 0; $i < 15; $i++) {
            Thing::create([
                'name' => 'Вещь ' . ($i + 1),
                'description' => 'Описание вещи №' . ($i + 1),
                'wrnt' => now()->addYears(rand(1, 3)),
                'amount' => rand(3, 10),
                'master_id' => $myId,
                'place_id' => $places->random()->id,
            ]);
        }

        // Создаём другого пользователя
        $other = User::firstOrCreate(['email' => 'other@example.com'], [
            'name' => 'Другой юзер',
            'password' => bcrypt('12345678'),
        ]);

        // 5 передач от тебя другому
        for ($i = 0; $i < 5; $i++) {
            Usage::create([
                'thing_id' => Thing::inRandomOrder()->first()->id,
                'user_id' => $other->id,
                'amount' => rand(1, 3),
            ]);
        }

        $this->command->info('Заполнено! 15 вещей, 5 мест, 5 передач для пользователя ID ' . $myId);
    }
}
