<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'icon',
        'is_active',
        'sort_order',
    ];

    public function subcategories()
    {
        return $this->hasMany(\App\Models\Subcategory::class);
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }
}