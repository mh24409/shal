<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class QuestionAnswer extends Model
{

  protected $with = ['question_answer_translations'];

  public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $question_answer_translation = $this->question_answer_translations->where('lang', $lang)->first();
      return $question_answer_translation != null ? $question_answer_translation->$field : $this->$field;
  }

  public function question_answer_translations(){
    return $this->hasMany(QuestionAnswerTranslation::class);
      }
     public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
