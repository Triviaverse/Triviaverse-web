<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizResult;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'user_id', 'completed'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function results()
    {
        return $this->hasOne(QuizResult::class);
    }
}
