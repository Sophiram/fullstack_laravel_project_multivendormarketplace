<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionRule extends Model
{
    protected $table = 'commission_rules';
    protected $primaryKey = 'commission_id';

    protected $fillable = [
        'category_id',
        'commission_rate',
        'status',
    ];

    protected $casts = [
        'commission_rate' => 'float',
    ];

    // បង្កើត Relation ភ្ជាប់ទៅកាន់ Table Categories
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
