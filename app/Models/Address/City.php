<?php

namespace App\Models\Address;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the province that owns the City
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get all of the Address for the City
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    
    public function getApiResponseAttribute()
    {
        return [
            'uuid' => $this->uuid,
            'province' => $this->province->only(['uuid', 'name']),
            'external_id' => $this->external_id,
            'name' => $this->name,
        ];
    }
}
