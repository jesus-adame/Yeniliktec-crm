<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
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

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->folio = IdGenerator::generate([
                'table'  => $this->table,
                'length' => 6,
                'prefix' =>date('y'),
            ]);
        });
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function documents()
    {
        return $this->morphToMany(Document::class, 'documentable');
    }
}
