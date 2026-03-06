<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'sku', 'description', 'price', 'cost', 'image_url', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'cost'  => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getMarginAttribute(): float
    {
        if ($this->price == 0) return 0;
        return round((($this->price - $this->cost) / $this->price) * 100, 1);
    }

    public function getStockStatusAttribute(): string
    {
        $qty = $this->inventory?->quantity ?? 0;
        $threshold = $this->inventory?->low_stock_threshold ?? 10;
        if ($qty <= 0) return 'out';
        if ($qty <= $threshold) return 'low';
        return 'ok';
    }
}
