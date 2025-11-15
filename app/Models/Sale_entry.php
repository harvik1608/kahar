<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale_entry extends Model
{
    use SoftDeletes;    

    // protected $table = "fishes";
    protected $fillable = ['customer_id','amount','date','time','payment_type','payment_method','note','avatar','is_active','created_by','updated_by'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
