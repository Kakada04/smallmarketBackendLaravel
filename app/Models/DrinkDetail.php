<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrinkDetail extends Model
{
    //
    use HasFactory;
    protected $table = "drink_details";
    protected $fillable = [
        "product_id",
        "description",];
}
