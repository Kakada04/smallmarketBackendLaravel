<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        "category_id",
        "product_name",
        "quantity",
        "cost_price",
        "sell_price",
        "banner_img",
        "description",
       
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function clothesDetails()
    {
        return $this->hasOne(ClothDetail::class);
    }

    public function drinkDetails()
    {
        return $this->hasOne(DrinkDetail::class);
    }

    public function skincareDetails()
    {
        return $this->hasOne(SkincareDetail::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
