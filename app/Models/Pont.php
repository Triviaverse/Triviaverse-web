<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pont extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pontok';

    protected $fillable = [
        'targy',
        'osszpont',
        'email',
    ];

    public function targy()
    {
        return $this->belongsTo(Targy::class);
    }
};
