<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get all house previews for this customer.
     *
     * @return HasMany
     */
    public function housePreviews(): HasMany
    {
        return $this->hasMany(HousePreview::class)->latest();
    }

    /**
     * Get the latest house preview for this customer.
     *
     * @return HasOne
     */
    public function latestPreview(): HasOne
    {
        return $this->hasOne(HousePreview::class)->latestOfMany();
    }

    /**
     * Get pending house previews.
     *
     * @return HasMany
     */
    public function pendingPreviews(): HasMany
    {
        return $this->hasMany(HousePreview::class)
                    ->where('status', 'pending');
    }


    /**
     * Format phone number for display.
     *
     * @return string
     */
    public function getFormattedPhoneAttribute(): string
    {
        return $this->phone;
    }
}
