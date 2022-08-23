<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $connection   = 'dbtest';
    protected $table        = 't_sales';

    protected $guarded     = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function saleDesc()
    {
        return $this->hasMany(SaleDescription::class, 'sale_id', 'id');
    }


    protected $dates        = ['date_of_sale' => 'datetime:Y-m-d H:i:s'];
    protected $hidden       = ['created_at', 'updated_at'];
}
