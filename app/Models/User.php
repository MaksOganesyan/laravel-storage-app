<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

  
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Пользователь  хозяин  вещей
     */
    public function things()
    {
        return $this->hasMany(Thing::class, 'master_id');
    }

    /**
     * Пользователь  использовать много вещей
     */
    public function usages()
    {
        return $this->hasMany(Usage::class);
    }

   
    public function places()
    {
        return $this->hasMany(Place::class);
    }
    /**
 * Передача
 */
    public function receivedThings()
    {
        return $this->belongsToMany(Thing::class, 'usages')
                ->withPivot('amount')
                ->withTimestamps();
}
}
