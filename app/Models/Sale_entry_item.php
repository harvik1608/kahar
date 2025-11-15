<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale_entry_item extends Model
{
    use SoftDeletes;    

    // protected $table = "fishes";
    protected $fillable = ['sale_entry_id','vendor_id','fish_id','quantity','amount','note','is_active'];
}
