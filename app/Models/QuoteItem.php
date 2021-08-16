<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'product_id',
        'name',
        'description',
        'quantity',
        'price',
        'unity',
        'tax_amount',
        'total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeSubtotal($query)
    {
        return $query->addSelect('quantity * price as subtotal');
    }
}
