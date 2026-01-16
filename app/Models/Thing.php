<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thing extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'wrnt',
        'master_id',
        'amount',
        'place_id',
    ];

    protected $casts = [
        'wrnt'   => 'date',
        'amount' => 'integer',
    ];

    public function master()
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function usages()
    {
        return $this->hasMany(Usage::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'usages')
                    ->withPivot('amount')
                    ->withTimestamps();
    }

    public function getAvailableAmountAttribute()
    {
        return $this->amount;
    }

    public function getWrntFormattedAttribute()
    {
        return $this->wrnt ? $this->wrnt->format('d.m.Y') : 'Не указана';
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public $available_amount; 
}
