<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswerTranslation extends Model
{
  protected $fillable = ['question','answer', 'lang', 'custom_question_id'];

  public function custom_question(){
    return $this->belongsTo(CustomQuestion::class);
  }
}
