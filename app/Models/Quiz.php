<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Question;
use App\Models\QuizAttempt;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'created_by', 'is_active', 'time_limit'];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}

