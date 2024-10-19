<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function getApiResponseAttribute(){
        return [
            'slug'=>$this->slug,
            'name'=>$this->name,
            'icon'=>asset($this->icon),
            'childs'=>$this->childs->pluck('api_response_child'),
            'description'=>$this->description
        ];
    }

    public function getApiResponseChildAttribute(){
        return [
            'slug'=>$this->slug,
            'name'=>$this->name,
            'description'=>$this->description
        ];
    }

}