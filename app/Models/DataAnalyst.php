<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataAnalyst extends Model
{
    //
    protected $table = "data_analysts";
    protected $fillable = [
        "report_date",
        "total_id",
        "total_revenue",
        "total_orders",
        "products_sold",
        "average_oder_value",
       
        ] ;
}
