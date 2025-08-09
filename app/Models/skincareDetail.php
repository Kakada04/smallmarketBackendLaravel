<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class skincareDetail extends Model
{
    //
    protected $table = "skincare_details";
    protected $fillable = [
        "product_id",
        "description",];
}
