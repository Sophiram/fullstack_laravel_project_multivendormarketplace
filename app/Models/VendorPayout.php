<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPayout extends Model
{
    protected $fillable = [
        'vendor_id',
        'amount',
        'bank_details_snapshot',
        'status',
        'transaction_receipt'
    ];
}
