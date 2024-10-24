<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'star_seller' => 'integer',
        'attachments' => 'array',
        'show_username' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAttachmentsAttribute($value)
    {
        // add storage path to each attachment
        $attachments = json_decode($value);
        $attachments = array_map(function ($attachment) {
            return asset('storage/' . $attachment);
        }, $attachments);

        return $attachments;
    }

    // Get API response
    public function getApiResponseAttribute()
    {
        return [
            'star_seller' => $this->star_seller,
            'variations' => $this->variations,
            'description' => $this->description,
            'attachments' => $this->attachments,
            'show_username' => $this->show_username,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'user_photo' => $this->show_username ? $this->user->photo_url : null,
            'user_name' => $this->show_username ? $this->user->name : substr($this->user->name, 0, 1) . str_repeat('*', strlen($this->user->name) - 2) . substr($this->user->name, -1),
        ];
    }

    public function setAttachmentsAttribute($value)
    {
        $this->attributes['attachments'] = json_encode($value);
    }
}