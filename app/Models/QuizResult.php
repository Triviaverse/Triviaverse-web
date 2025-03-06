<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QuizAttempt;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_attempt_id', 'score_percentage', 'is_overridden'];

    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }
}
