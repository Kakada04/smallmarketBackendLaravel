<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = "categories";
    protected $fillable = ["type",'category_image'] ;

     public function products()
    {
        return $this->hasMany(Product::class);
    }
}
