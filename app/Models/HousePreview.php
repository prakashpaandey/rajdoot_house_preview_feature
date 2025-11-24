<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HousePreview extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'colors',
        'png_image',
        'svg_image',
        'customer_message',
        'status',
        'processed_by',
        'processed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array<int, string>
     */
    protected $with = ['customer'];

    /**
     * Append custom attributes to model array/JSON.
     *
     * @var array<int, string>
     */
    protected $appends = ['png_image_url', 'svg_image_url', 'colors_array'];

    /**
     * Get the customer that owns this house preview.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who processed this preview.
     *
     * @return BelongsTo
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the full URL for the PNG image.
     *
     * @return string|null
     */
    public function getPngImageUrlAttribute(): ?string
    {
        if ($this->png_image) {
            return asset('storage/' . $this->png_image);
        }
        return null;
    }

    /**
     * Get the full URL for the SVG image.
     *
     * @return string|null
     */
    public function getSvgImageUrlAttribute(): ?string
    {
        if ($this->svg_image) {
            return asset('storage/' . $this->svg_image);
        }
        return null;
    }

    /**
     * Get colors as an array from delimited string.
     *
     * @return array|null
     */
    public function getColorsArrayAttribute(): ?array
    {
        if ($this->colors) {
            return explode('::', $this->colors);
        }
        return null;
    }

    /**
     * Set colors from array to delimited string.
     *
     * @param array|string $value
     * @return void
     */
    public function setColorsAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['colors'] = implode('::', $value);
        } else {
            $this->attributes['colors'] = $value;
        }
    }

    /**
     * Scope a query to only include pending previews.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include processing previews.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope a query to only include completed previews.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include cancelled previews.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Mark this preview as processed.
     *
     * @param int|null $userId
     * @return bool
     */
    public function markAsProcessed(?int $userId = null): bool
    {
        return $this->update([
            'status' => 'completed',
            'processed_by' => $userId,
            'processed_at' => now(),
        ]);
    }
}
