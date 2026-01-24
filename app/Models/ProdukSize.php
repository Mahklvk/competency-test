<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukSize extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'size',
        'produk_id',
    ];

    public function sizes(): BelongsTo
    {
        return $this->hasMany(Produk::class, 'size');
    }

    public function produk(): BelongsTo
{
    return $this->belongsTo(Produk::class, 'produk_id');
}

public function transactions(): HasMany
{
    return $this->hasMany(ProductTransaction::class, 'produk_size');
}
}
