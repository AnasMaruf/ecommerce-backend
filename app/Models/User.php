<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Address\Address;
use App\Models\Product\Product;
use App\Models\Product\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
        //     'name',
        //     'email',
        //     'password',
        // ];

    protected $guarded = ['id'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getApiResponseAttribute(){
        return [
            "name" => $this->name,
            "email" => $this->email,
            "photo_url" => $this->photo_url,
            "username" => $this->username,
            "phone" => $this->phone,
            "store_name" => $this->store_name,
            "gender" => $this->gender,
            "birth_date" => $this->birth_date
        ];
    }

    public function getApiResponseAsSellerAttribute()
    {
        $productIds = $this->products()->pluck('id');

        return [
            'username' => $this->username,
            'store_name' => $this->store_name,
            'photo_url' => $this->photo_url,
            'product_count' => $this->products()->count(),
            'rating_count' => Review::whereIn('product_id', $productIds)->count(),
            'join_date' => $this->created_at->diffForHumans(),
            'send_from' => optional($this->addresses()->where('is_default', true)->first())->getApiResponseAttribute()
        ];
    }

    public function getPhotoUrlAttribute(){
        if (is_null($this->photo)) {
            return null;
        }
        return asset('storage/'.$this->photo);
    }

    /**
     * Get all of the Address for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }
}
