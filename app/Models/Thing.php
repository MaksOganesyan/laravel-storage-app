<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thing extends Model
{
    use HasFactory;

    /**
     * Поля, которые можно массово заполнять (через create или fill)
     */
    protected $fillable = [
        'name',
        'description',
        'wrnt',
        'master_id',
    ];

    /**
     * Отношение: вещь принадлежит одному хозяину (пользователю)
     */
    public function master()
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    /**
     * Отношение: вещь может использоваться многими пользователями (через таблицу usages)
     */
    public function usages()
    {
        return $this->hasMany(Usage::class);
    }

    /**
     * Геттер: красивое отображение даты гарантии (если нужно в шаблонах)
     */
    public function getWrntFormattedAttribute()
    {
        return $this->wrnt ? $this->wrnt->format('d.m.Y') : 'Не указана';
    }
    protected $casts = [
    'wrnt' => 'date',  
];
}
