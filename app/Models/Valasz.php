<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Valasz extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'valaszok';

    protected $fillable = [
        'kerdes_id',
        'valasz',
        'helyes',
    ];

    public function kerdes()
    {
        return $this->belongsTo(Kerdes::class);
    }
};

