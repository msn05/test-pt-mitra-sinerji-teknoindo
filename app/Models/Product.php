<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $connection = 'dbtest';
    use HasFactory, SoftDeletes;

    protected $table = 'barangs';

    protected $fillable = ['code', 'name', 'price'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function salesDecs()
    {
        return $this->belongsToMany(SaleDescription::class, 't_sales_det', 'product_id', 'id')->withPivot('product_id');
        // return BelongsToMany(SaleDescription::class,'t_sales_det','pr');
        // return belongsToMany(SaleDescription::class, 't_sales_det', 'product_id', 'id');
    }
}
