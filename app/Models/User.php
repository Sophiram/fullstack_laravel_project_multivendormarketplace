<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role',
        'is_approved',
        'gender',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function orders()
    {
        // ប្រសិនបើ User មួយមាន Order ច្រើន
        return $this->hasMany(Order::class, 'user_id');
    }


    public function isVendor() {
        return $this->role == 1; // ត្រូវប្រាកដថាវាត្រូវនឹង $roleValue របស់អ្នក
    }

    public function isCustomer() {
        return $this->role == 2;
    }
    public function vendor()
    {
        // សន្មតថា User ម្នាក់មាន Vendor profile មួយ
        return $this->hasOne(Vendor::class, 'user_id');
    }
    public function stores()
    {
        // ដោយសារក្នុង Table stores របស់អ្នកប្រើ 'vendor_id' ជា Foreign Key
        // ប្រសិនបើ 'vendor_id' នោះជា ID របស់ User តែម្តង កូដនឹងដំណើរការត្រឹមត្រូវ
        return $this->hasMany(Store::class, 'vendor_id');
    }

    // ក្នុង app/Models/User.php
    public function payoutRequests()
    {
        return $this->hasMany(PayoutRequest::class);
    }
}
