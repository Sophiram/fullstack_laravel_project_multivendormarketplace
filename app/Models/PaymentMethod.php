<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'credentials',
        'logo',
        'qr_code',
        'status'
    ];
   protected $casts = [
        'credentials' => 'array',
        'status' => 'boolean',
    ];
    public function getApiKeyAttribute()
    {
        return $this->credentials['secret_key'] ?? env('KHQR_TOKEN');
    }
}
