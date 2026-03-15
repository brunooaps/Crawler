<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'price', 'description', 'category'];
    
    public function images() {
        return $this->hasMany(Image::class);
    }
}
