<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'courier_price' => 'float',
        'voucher_value' => 'float',
        'voucher_cashback' => 'float',
        'service_fee' => 'float',
        'total' => 'float',
        'pay_with_coin' => 'float',
        'total_payment' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function address()
    {
        return $this->belongsTo(\App\Models\Address\Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->hasOne(\App\Models\Voucher::class, 'id', 'voucher_id');
    }

    public function getApiResponseAttribute()
    {
        $subTotal = $this->items->sum('total');

        return [
            'uuid' => $this->uuid,
            'address' => optional($this->address)->api_response,
            'courier' => $this->courier,
            'courier_type' => $this->courier_type,
            'courier_estimation' => $this->courier_estimation,
            'courier_price' => $this->courier_price,
            'voucher' => optional($this->voucher)->api_response,
            'subtotal' => $subTotal,
            'voucher_value' => $this->voucher_value,
            'voucher_cashback' => $this->voucher_cashback,
            'service_fee' => $this->service_fee,
            'total' => $this->total,
            'pay_with_coin' => $this->pay_with_coin,
            'total_payment' => $this->total_payment,
        ];
    }
}
