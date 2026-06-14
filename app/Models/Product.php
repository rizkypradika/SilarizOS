<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $appends = ['brand'];

    /**
     * Extract the brand/service name from the product name.
     * E.g. "Netflix 1 Bulan Sharing" → "Netflix"
     *      "Disney+ Hotstar 3 Bulan" → "Disney+ Hotstar"
     *      "Spotify Premium 1 Bulan" → "Spotify Premium"
     */
    public function getBrandAttribute(): string
    {
        preg_match('/^([\D]+?)(?=\s+\d|\s*$)/', $this->name ?? '', $matches);
        return trim($matches[1] ?? $this->name ?? '');
    }
}
