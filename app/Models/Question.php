<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Quiz;
use App\Models\Answer;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'question_text', 'type'];

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }
}
