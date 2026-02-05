<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'price', 'image', 'stock', 'category_id'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function comparers(){
        return $this->hasMany(Comparer::class);
    }
}
