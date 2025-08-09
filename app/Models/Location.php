<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $table = "locations";
    protected $fillable = [
        "location"] ;

         public function users()
    {
        return $this->hasMany(User::class);
    }
}
