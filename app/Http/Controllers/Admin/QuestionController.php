<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $nid = $request->input('nid');
        $qid = $request->input('qid');
        $type = $request->input('type');
        $content = $request->input('content');
        $options = $request->input('options');

        if (!empty($qid)){
            $question = Question::find($qid);  
        }else{
            $question = new Question;
            $question->q_type=$type;
            $question->n_id=$nid;
        }
        $question->content = $content;
        $question->save();
        $qid = $question->id;
        if (!empty($options)){
            foreach ($options as $option){
                if (!empty($option['oid'])){
                    $optionModel = QuestionOption::find($option['oid']);
                }else{
                    $optionModel = new QuestionOption;
                }
                $optionModel->o_value = $option['value'];
                $optionModel->n_id = $nid;
                $optionModel->q_id = $qid;
                $optionModel->save();
            }
        }

        return response()->json([
            'success' => true
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Question::destroy($id);
        return response()->json([
            'success' => true
        ]);
    }
}
