<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Поля, которые можно массово заполнять
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Скрытые поля при сериализации
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Преобразование типов
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Пользователь может быть хозяином многих вещей
     */
    public function things()
    {
        return $this->hasMany(Thing::class, 'master_id');
    }

    /**
     * Пользователь может использовать (брать) много вещей
     */
    public function usages()
    {
        return $this->hasMany(Usage::class);
    }

    /**
     * Пользователь может иметь много своих мест хранения
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
