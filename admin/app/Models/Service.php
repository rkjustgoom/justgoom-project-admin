<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'product_name',
        'product_image',
        'product_desc',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }

    public function scopeProducts($query)
    {
        return $query->where('type', 'product');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
