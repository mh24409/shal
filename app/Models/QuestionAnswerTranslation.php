<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswerTranslation extends Model
{
  protected $fillable = ['question','answer', 'lang', 'question_answer_id'];

  public function question_answer(){
    return $this->belongsTo(QuestionAnswer::class);
  }
}
