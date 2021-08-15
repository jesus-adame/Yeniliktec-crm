<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }
}
