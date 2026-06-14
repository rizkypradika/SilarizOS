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

    protected static function booted()
    {
        static::creating(function ($order) {
            $lastOrder = self::orderBy('id', 'desc')->first();
            $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
            $order->order_number = 'SLRIZ' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }

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
