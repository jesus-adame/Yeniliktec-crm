<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id', 'name', 'tex_color', 'bg_color'
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
