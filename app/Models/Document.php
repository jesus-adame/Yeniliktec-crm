<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'mime_type',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($document) {
            $document->leads()->detach();
            $document->contact()->detach();
        });
    }

    public function getExtensionAttribute()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function leads()
    {
        return $this->morphedByMany(Lead::class, 'documentable');
    }

    public function contact()
    {
        return $this->morphedByMany(Lead::class, 'documentable');
    }
}
