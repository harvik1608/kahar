<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fish extends Model
{
    use SoftDeletes;    

    protected $table = "fishes";
    protected $fillable = ['name','avatar','is_active','is_approved'];
}
