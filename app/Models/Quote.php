<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
        'user_id',
        'client_id',
        'lead_id',
        'status',
    ];

    public static function boot() {
        parent::boot();

        static::deleting(function($quote) {
            $quote->items()->delete();
        });
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function scopeSelectTotal($query)
    {
        return $query
            ->addSelect(DB::raw('(select SUM(quote_items.price * quote_items.quantity + (quote_items.price * quote_items.quantity * (quote_items.tax_amount / 100)))
            from `quote_items` where `quotes`.`id` = `quote_items`.`quote_id`) as total'));
    }
}
