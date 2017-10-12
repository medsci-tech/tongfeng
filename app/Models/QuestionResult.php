<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Questionnaire;

class QuestionResult extends Model
{
    protected $table = 'q_question_result';
    //

    static public function getActiveQuestionNaire($studentId){
        $aciviveNaire = Questionnaire::where('n_status',1)->first();
        if ($aciviveNaire == null) {
            return null;
        }
        $result = QuestionResult::where('n_id',$aciviveNaire->id)->where('s_id',$studentId)->first();
        if ($result  == null){
            return $aciviveNaire;
        }
        return null;
    }
}
