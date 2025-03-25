<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use App\Models\QuestionAnswerTranslation;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class QuestionAnswerController extends Controller
{
    public function index(Request $request)
    {
        $sort_search =null;
        $questions_answers = QuestionAnswer::orderBy('created_at', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $questions_answers = $questions_answers->where('question', 'like', '%'.$sort_search.'%')->orWhere('answer', 'like', '%'.$sort_search.'%');
        }
        $questions_answers = $questions_answers->paginate(15);
        return view('backend.product.questions_answers.index', compact('questions_answers', 'sort_search'));
    }


    public function create()
    {
    }


    public function store(Request $request)
    {
        $qustion_answer = new QuestionAnswer;
        $qustion_answer->question = $request->question;
        $qustion_answer->answer = $request->answer;
        
        $qustion_answer->save();
        
        $qustion_answer_translation = QuestionAnswerTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'question_answer_id' => $qustion_answer->id]);
        $qustion_answer_translation->question = $request->question;
        $qustion_answer_translation->answer = $request->answer;
        $qustion_answer_translation->save();

        flash(translate('question answer has been inserted successfully'))->success();
        return redirect()->route('questions_answers.index');

    }

 
    public function show($id)
    {
        //
    }

 
    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $qustion_answer  = QuestionAnswer::findOrFail($id);
        return view('backend.product.questions_answers.edit', compact('qustion_answer','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

         $qustion_answer = QuestionAnswer::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $qustion_answer->question = $request->question;
             $qustion_answer->answer = $request->answer;
        }

        $qustion_answer->save();

        $qustion_answer_translation = QuestionAnswerTranslation::firstOrNew(['lang' => $request->lang, 'question_answer_id' => $qustion_answer->id]);
        $qustion_answer_translation->question = $request->question;
        $qustion_answer_translation->answer = $request->answer;
        $qustion_answer_translation->save();

        flash(translate('question answer has been updated successfully'))->success();
        return back();

    }


    public function destroy($id)
    {
        $qustion_answer = QuestionAnswer::findOrFail($id); 
        $qustion_answer->products()->detach(); 
        QuestionAnswer::destroy($id);
        
        flash(translate('Question & Answer has been deleted successfully'))->success();
        return redirect()->route('questions_answers.index');

    }
}
