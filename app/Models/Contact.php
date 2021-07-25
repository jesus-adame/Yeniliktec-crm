<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone_number',
        'type',
        'status',
        'description',
        'billing_name',
        'billing_code',
        'billing_address',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
