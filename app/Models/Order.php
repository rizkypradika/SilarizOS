<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'total_price'  => 'decimal:2',
        'quantity'     => 'integer',
        'account_info' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a comma-separated summary of ordered product names.
     */
    public function getItemsSummaryAttribute(): string
    {
        return $this->items->map(function ($item) {
            return "{$item->product?->name} (x{$item->quantity})";
        })->join(', ');
    }
}
