<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'owner_name',
        'tagline',
        'logo',
        'phone',
        'whatsapp_no',
        'email',
        'business_desc',
        'address',
        'zipcode',
        'country',
        'state',
        'city',
        'business_hours',
        'social_website',
        'social_subwebsite',
        'social_facebook',
        'social_twitter',
        'social_linkedin',
    ];

    protected $casts = [
        'business_hours' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
