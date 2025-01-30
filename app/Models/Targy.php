<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Targy extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'targyak';

    protected $fillable = [
        'targyneve',
    ];

    public function kerdesek()
    {
        return $this->hasMany(Kerdes::class);
    }

    public function pontok()
    {
        return $this->hasMany(Pont::class);
    }
};
