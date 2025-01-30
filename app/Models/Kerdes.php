<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kerdes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kerdesek';

    protected $fillable = [
        'kerdes',
        'targy',
    ];

    public function targy()
    {
        return $this->belongsTo(Targy::class);
    }

    public function valaszok()
    {
        return $this->hasMany(Valasz::class);
    }
};
