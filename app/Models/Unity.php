<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unity extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
    ];

    private static $unities = [
        [ 'slug' => 'hours',    'name' => 'hrs'],
        [ 'slug' => 'proyect',  'name' => 'proyecto' ],
        [ 'slug' => 'weeks',    'name' => 'semanas' ],
        [ 'slug' => 'mounths',  'name' => 'meses' ],
        [ 'slug' => 'years',    'name' => 'años' ],
    ];

    public static function getUnities()
    {
        return self::$unities;
    }
}
