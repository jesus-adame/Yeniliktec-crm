<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'created_by',
        'title',
        'description',
        'start',
        'end',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
