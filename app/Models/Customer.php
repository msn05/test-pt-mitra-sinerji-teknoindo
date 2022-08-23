<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $connection = 'dbtest';
    protected $table = 'm_customers';
    protected $hidden = ['created_at', 'updated_at'];

    public function salesDecs()
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'id');
    }
}
