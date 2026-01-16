<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'repair',
        'work',
    ];

    public function usages()
    {
        return $this->hasMany(Usage::class);
    }
    public function things()
    {
        return $this->hasMany(Thing::class);
    }
}
