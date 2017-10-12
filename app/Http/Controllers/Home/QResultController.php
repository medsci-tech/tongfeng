<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Http\Requests;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionResult;
class QResultController extends WebController
{

    public function success(Request $request) {
        return view('home.question-success');
    }

    public function answer(Request $request, $nid) {
        $oldResult = QuestionResult::where('n_id',$nid)->where('s_id',$this->studentId)->first();
        if (!empty($oldResult)){
            return response()->json([
                'success' => true
            ]);
        }
        $answers = json_decode($request->getContent(),true);
        foreach ($answers as $answer){
            $qid = $answer['qid'];
            $choices = $answer['choices'];
            $type = $answer['type'];
            $text = $answer['text'];

            if ($type=='填空'){
                $result = new QuestionResult;
                $result->s_id=$this->studentId;
                $result->n_id=$nid;
                $result->q_id=$qid;
                $result->input_text=$text;
                $result->save();
            }else{
                foreach ($choices as $choice) {
                    $result = new QuestionResult;
                    $result->s_id=$this->studentId;
                    $result->n_id=$nid;
                    $result->q_id=$qid;
                    $result->o_id=$choice;
                    $result->save();
                }
            }
           
        }
        return response()->json([
            'success' => true
        ]);
    }
    public function index($nid)
    {
        $naire = Questionnaire::find($nid);
        $questions = Question::where('n_id',$nid)->orderBy('id')->get();
        return view('home.question',[
            'naire' =>$naire,
            'questions' =>$questions
        ]);
    }
}
