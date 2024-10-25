<?php

namespace App\Models\Product;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'price' => 'float',
        'price_sale' => 'float',
        'stock' => 'integer',
        'weight' => 'float',
        'length' => 'float',
        'width' => 'float',
        'height' => 'float'
    ];

    /**
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all of the images for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Get all of the variations for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations(): HasMany
    {
        return $this->hasMany(Variation::class);
    }

    /**
     * Get the seller that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    /**
     * Get all of the reviews for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getRatingAttribute(){
        return round($this->reviews->avg('star_seller'),2);
    }

    public function getRatingCountAttribute(){
        return (float) $this->reviews->count();
    }

    public function getPriceDiscountPercentageAttribute(){
        if (is_null($this->price_sale)) return null;
        return (float) round(($this->price - $this->price_sale)/$this->price * 100,2);
    }

    public function getFormattedCreatedAttribute(){
        return $this->created_at->format('d F Y');
    }

    public function getVideoUrlAttribute(){
        return $this->video ? asset('storage/'.$this->video): null;
    }

    public function getSaleCountAttribute(){
        return 0;
    }

    public function getApiResponseExcerptAttribute(){
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'price_sale' => $this->price_sale ?: null,
            'price_discount_percentage' => $this->price_discount_percentage,
            'sale_count' => $this->getSaleCountAttribute(),
            'image_url' => $this->images->first()->image_url,
            'stock' => $this->stock
        ];
    }

    public function getApiResponseAttribute(){
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'price_sale' => $this->price_sale ?: null,
            'rating' => $this->rating,
            'rating_count' => $this->rating_count,
            'sale_count' => $this->getSaleCountAttribute(),
            'price_discount_percentage' => $this->price_discount_percentage,
            'stock' => $this->stock,
            'category' => $this->category->getApiResponseWithParentAttribute(),
            'description' => $this->description,
            'weight' => $this->weight,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'video_url' => $this->video_url,
            'seller' => $this->seller->getApiResponseAsSellerAttribute(),
            'images' => $this->images->map(function ($image) {
                return $image->image_url;
            }),
            'variations' => $this->variations->map(function ($variation) {
                return $variation->getApiResponseAttribute();
            }),
            'review_summary' => [
                '5' => $this->reviews()->where('star_seller', 5)->count(),
                '4' => $this->reviews()->where('star_seller', 4)->count(),
                '3' => $this->reviews()->where('star_seller', 3)->count(),
                '2' => $this->reviews()->where('star_seller', 2)->count(),
                '1' => $this->reviews()->where('star_seller', 1)->count(),
                'with_attachment' => $this->reviews()->whereNotNull('attachments')->count(),
                'with_description' => $this->reviews()->whereNotNull('description')->count(),
            ],
            'other_product' => $this->seller->products()->where('id', '!=', $this->id)->limit(6)->get()->map(function ($product) {
                return $product->getApiResponseExcerptAttribute();
            }),
        ];
    }
}
