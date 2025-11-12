<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase_entry extends Model
{
    use SoftDeletes;    

    // protected $table = "fishes";
    protected $fillable = ['vendor_id','fish','date','time','note','avatar','is_active','created_by','updated_by'];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }
}
