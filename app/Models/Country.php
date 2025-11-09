<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;    

    // protected $table = "fishes";
    protected $fillable = ['name','code','emoji','unicode','image','dial_code','is_active'];
}
