<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothDetail extends Model
{
    //
    protected $table = "clothes_details";
    protected $fillable = [
        "product_id",
        "size",
        "description",
    ];

     public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
