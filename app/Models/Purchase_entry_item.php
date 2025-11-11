<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase_entry_item extends Model
{
    use SoftDeletes;    

    // protected $table = "fishes";
    protected $fillable = ['purchase_entry_id','fish_id','quantity','amount','note','is_active'];
}
