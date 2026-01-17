<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Админ — создаём или обновляем (не падает на дубликат)
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Админ',
                'password' => bcrypt('12345678'),
                'is_admin' => true,
            ]
        );

        // Тестовый пользователь — тоже firstOrCreate
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('12345678'),
                'is_admin' => false,
            ]
        );

        $this->command->info('Админ создан/обновлён: admin@example.com / 12345678');
        $this->command->info('Тестовый пользователь: test@example.com / 12345678');
    }
}
