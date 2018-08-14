<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $fillable = [
        'code', 'name', 'description', 'stock', 'price', 'category_id'
    ];

    // protected $guarded = [];
}
