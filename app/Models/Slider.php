<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getApiResponseAttribute(){
        return [
            'title'=>$this->title,
            'image'=>asset($this->image),
            'url'=>$this->url
        ];
    }
}
