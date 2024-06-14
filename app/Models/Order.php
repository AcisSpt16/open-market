<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'buyer_name', 'email', 'contact_number', 'order_date',
        'delivery_address','order_status'
    ];



    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
