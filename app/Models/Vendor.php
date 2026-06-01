<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commission_rate',
        'approval_status',
        'bank_account_info',
    ];

    // ទំនាក់ទំនង: Vendor ម្នាក់អាចមានហាងច្រើន
    public function stores()
    {
        return $this->hasMany(Store::class, 'vendor_id');
    }

    // ទំនាក់ទំនង: Vendor ជាកម្មសិទ្ធិរបស់ User ម្នាក់
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCommissionRateForCategory($categoryId)
    {
        // ស្វែងរក Rule ណាដែលសកម្ម (Active) សម្រាប់ Category នោះ
        $rule = CommissionRule::where('category_id', $categoryId)
            ->where('status', 'Active')
            ->first();

        // ប្រសិនបើរកឃើញ ឱ្យទាញយកអត្រានោះ បើមិនមានទេ ឱ្យយកតម្លៃ Default (ឧទាហរណ៍៖ 0%)
        return $rule ? $rule->commission_rate : 0.00;
    }
}
