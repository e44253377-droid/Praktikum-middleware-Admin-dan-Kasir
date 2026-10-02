<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'code',
        'barcode',
        'category',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getPosCodeAttribute(): string
    {
        return $this->code ?: 'PRD-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }
}
