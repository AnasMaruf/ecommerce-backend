<?php

namespace App\Models\Address;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the user that owns the Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the city that owns the Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getApiResponseAttribute(){
        return [
            'uuid' => $this->uuid,
            'is_default' => (boolean) $this->is_default,
            'receiver_name' => $this->receiver_name,
            'receiver_phone' => $this->receiver_phone,
            'city' => $this->city->api_response,
            'district' => $this->district,
            'postal_code' => $this->postal_code,
            'detail_address' => $this->detail_address,
            'address_note' => $this->address_note,
            'type' => $this->type,
        ];
    }
}
