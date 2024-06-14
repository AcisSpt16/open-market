<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'order_id', 'product_name', 'product_description', 'quantity', 'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
