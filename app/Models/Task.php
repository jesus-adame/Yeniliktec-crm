<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'user_id', 'taskable_id', 'takskable_type', 'title', 'description', 'status', 'expire_at',
    ];
}
