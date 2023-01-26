<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{

    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        "id",
        "brand",
        "model",
        "year",
        "max_speed",
        "is_automatic",
        "engine",
        "number_of_doors"
    ];
    protected $casts = [
        'engine' => 'array'
    ];
}
