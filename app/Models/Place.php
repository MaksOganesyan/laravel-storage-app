<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    /**
     * Поля для массового заполнения
     */
    protected $fillable = [
        'name',
        'description',
        'repair',
        'work',
    ];

    /**
     * Место может использоваться для многих вещей
     */
    public function usages()
    {
        return $this->hasMany(Usage::class);
    }
}
