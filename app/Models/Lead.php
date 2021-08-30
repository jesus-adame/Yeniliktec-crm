<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'contact_id', 'agent_id', 'column_id', 'title', 'description', 'status',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function documents()
    {
        return $this->morphToMany(Document::class, 'documentable');
    }
}
