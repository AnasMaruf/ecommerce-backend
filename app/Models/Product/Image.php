<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }
}
