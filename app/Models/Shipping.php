<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'order_id',
        'shipping_company_id', // ប្រើ ID ជំនួសឱ្យការសរសេរឈ្មោះផ្ទាល់
        'tracking_number',
        'shipping_status',
        'shipping_cost',
        'shipped_at',
        'delivered_at',
        'notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shippingCompany()
    {
        // ត្រូវប្រាកដថា ForeignKey ក្នុង Database ឈ្មោះ shipping_company_id
        return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
    }
}
