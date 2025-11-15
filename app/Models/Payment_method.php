<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment_method extends Model
{
    use SoftDeletes;    

    // protected $table = "fishes";
    protected $fillable = ['name','avatar','is_active','is_approved','created_by','updated_by'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
