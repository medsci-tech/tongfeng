<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionResult;
use App\Models\Student;
use App\Models\ThyroidClassPhase;
use Illuminate\Http\Request;
use DB;
/**
 * Class StatisticController
 * @package App\Http\Controllers\Admin
 */
class QuestionnairController extends Controller
{
      /**
     * Data filtering.
     * @param Request $request
     * @return array
     */
     private function formatData($request)
     {
         $data = [
             'title' => $request->input('title'),
             'description' => $request->input('description'),
             'n_status' => $request->input('n_status')
         ];
 
         return $data;
     }

    public function index(Request $request)
    {
        return view('backend.question.index',[
            'nairs' => Questionnaire::paginate('10'),
        ]);
    }

    public function summary(Request $request){
        $result = DB::table('q_question_result')
            ->select(DB::raw('count(distinct s_id) as user_count, n_id'))
            ->groupBy('n_id')
            ->get();
        $result1 = array();
        foreach($result as $r){
            $result1[$r->n_id]=$r->user_count;
        }
        return view('backend.question.summary',[
            'nairs' => Questionnaire::paginate('10'),
            'users' => $result1
        ]);
    }

    public function summaryDetail(Request $request,$nid){
        $naire = Questionnaire::find($nid);
        $questions = Question::where('n_id',$nid)->where('q_type','!=','填空')->orderBy('id')->get();

        $result = DB::table('q_question_result')
            ->select(DB::raw('count(*) as option_count, o_id'))
            ->where('n_id', $nid)
            ->whereNotNull('o_id')
            ->groupBy('o_id')
            ->get();
        $result1 = array();
        foreach($result as $r){
            $result1[$r->o_id]=$r->option_count;
        }
        return view('backend.question.summary-detail',[
            'naire' =>$naire,
            'questions' =>$questions,
            'result'=>$result1
        ]);
    }

    public function summaryUsers(Request $request,$nid){
        $naire = Questionnaire::find($nid);
        $votedStudents = Student::whereExists(function ($query) use ($nid) {
            $query->select(DB::raw(1))
                  ->from('q_question_result')
                  ->whereRaw("q_question_result.s_id = students.id AND q_question_result.n_id=$nid");
        })->paginate('10');

        return view('backend.question.summary_users',[
            'naire' =>$naire,
            'students' =>$votedStudents
        ]);
    }

    public function voteDetail(Request $request, $nid, $sid){
        $student = Student::find($sid);
        $naire = Questionnaire::find($nid);
        $questions = Question::where('n_id',$nid)->orderBy('id')->get();
        $answers = QuestionResult::where('n_id',$nid)->where('s_id',$sid)->get();
        return view('backend.question.voteDetail',[
            'naire' =>$naire,
            'questions' =>$questions,
            'answers' => $answers,
            'student'=>$student
        ]);
    }

    public function editQuestion(Request $request, $nid)
    {
        $naire = Questionnaire::find($nid);
        $questions = Question::where('n_id',$nid)->orderBy('id')->get();
        return view('backend.question.edit',[
            'naire' =>$naire,
            'questions' =>$questions
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->formatData($request);
        Questionnaire::create($data);
        \Session::flash('alert', [
            'type' => 'success',
            'title' => '新增成功',
            'message' => '添加问卷成功',
        ]);

        return redirect('/admin/naire');

    }
    public function update(Request $request, $id)
    {
        $data = $this->formatData($request);
        $teacher = Questionnaire::find($id);
        $teacher->update($data);

        if ($request->input('n_status')=='1'){
            Questionnaire::where('id','!=',$id)->update(['n_status'=>0]);
        }

        \Session::flash('alert', [
            'type' => 'success',
            'title' => '修改成功',
            'message' => '修改问卷成功',
        ]);

        return redirect('/admin/naire');
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
     {
        Questionnaire::destroy($id);
        Question::where('n_id', $id)->delete();
        return response()->json([
            'success' => true
        ]);
     }
}