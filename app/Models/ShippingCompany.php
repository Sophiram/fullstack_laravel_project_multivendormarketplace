<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'tracking_url_template',
        'shipping_fee', // បន្ថែមវាដើម្បីឱ្យអាច Save បាន
        'is_active'
    ];

    // ទំនាក់ទំនងទៅកាន់ Shipping (ប្រសិនបើមាន)
    public function shippings()
    {
        return $this->hasMany(Shipping::class, 'shipping_company_id');
    }
}
