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
        'unit_id',
    ];

    protected $casts = [
        'wrnt'   => 'date',
        'amount' => 'integer',
    ];

    protected $appends = [
        'available_amount',
        'wrnt_formatted',
    ];

    public function master()
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
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
        return $this->amount - ($this->usages_sum_amount ?? $this->usages()->sum('amount'));
    }

    public function getWrntFormattedAttribute()
    {
        return $this->wrnt ? $this->wrnt->format('d.m.Y') : 'Не указана';
    }
}
