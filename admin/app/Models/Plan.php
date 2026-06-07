<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'rate',
        'duration_days',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'duration_days' => 'integer',
    ];

    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }
}
