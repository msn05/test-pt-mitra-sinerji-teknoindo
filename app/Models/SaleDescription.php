<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDescription extends Model
{
    use HasFactory;

    protected $connection   = 'dbtest';
    protected $table        = 't_sales_det';
    protected $guarded      = [];


    public function product()
    {
        return $this->belongsTo(Product::class);
        // return $this->belongsToMany(Product::class, 't_sales_det', 'product_id', 'product_id');
    }

    // public function sale()
    // {
    //     // return $this->belongsToMany(Transaction::class, 't_sales_det', 'sale_id', 'id')->withPivot('grand_total', 'qty');

    //     // return $this->belongsTo(Transaction::class);
    // }
    protected $hidden       = ['created_at', 'updated_at'];
}
