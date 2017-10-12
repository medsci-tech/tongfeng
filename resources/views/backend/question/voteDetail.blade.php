@extends('backend.layouts.app')

@section('title','问卷编辑')

@section('css')

<link rel="stylesheet" href="/css/question.css">
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      @include('backend.layouts.alerts')
      <h1>
        用户问卷答案
      </h1>
      <p style="margin-top:10px;">
      <span>姓名：{{$student->name}}</span><span style="margin-left:30px;">电话：{{$student->phone}}</span>
      <p>
    </section>
    <section class="content">
    <div>
        <a href="/admin/naire/summary/users/{{$naire->id}}" style="float:right;" class="btn btn-large btn-info">返回 </a>
    </div>
    
    @foreach  ($questions as $index=>$question)

        @php
            $qid = $question->id;
            $selected_options = array();
            foreach ($answers as $answer) {
                if ($answer->q_id != $qid){
                    continue;
                }
                $selected_options[]=$answer->o_id;
            }
            
        @endphp
           
        <div style="background-color:white;margin-top: 2px;" >
        <div  class="question-item ivu-row-flex ivu-row-flex-top ivu-row-flex-start">
            <div  class="ivu-col ivu-col-span-6" style="width: 60px; text-align: center;margin: 0 0;">
                <h4 >Q{{$index+1}}:</h4> 
            </div> 
            @if ($question->q_type=='单选')
            <div  class="ivu-col ivu-col-span-18">
                <h4 >[单选] {{$question->content}} <span  style="color: rgb(255, 0, 0);">*</span>
                </h4> 
                <!----> 
                <div  class="question-options">
                    <div >
                        <div  class="ivu-radio-group ivu-radio-group-vertical" style="width: 100%;">
                       
                        @foreach  ($question->options as $option)
                            <label  class="option-item ivu-radio-wrapper ivu-radio-group-item ivu-radio-wrapper-disabled">
                                <span class="ivu-radio ivu-radio-disabled">
                                <input type="radio" disabled="disabled"  name="{{$option->id}}"
                                {{ in_array($option->id,$selected_options) ? 'checked': '' }}
                                /></span>
                                <span >{{$option->o_value}}</span> 

                            </label>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @elseif ($question->q_type=='多选')
            <div  class="ivu-col ivu-col-span-18">
                <h4 >[多选] {{$question->content}}  <span  style="color: rgb(255, 0, 0);">*</span>
      
                </h4> 
                <!----> 
                <div  class="question-options">
                <!----> 
                    <div >
                        <div  class="checkbox-list ivu-checkbox-group">
                        @foreach  ($question->options as $option)
                            <label  class="option-item ivu-checkbox-wrapper ivu-checkbox-group-item ivu-checkbox-wrapper-disabled"><span class="ivu-checkbox ivu-checkbox-disabled">
                           
                             <input type="checkbox" disabled="disabled" name="{{$option->id}}"
                                {{ in_array($option->id,$selected_options) ? 'checked': '' }} 
                             
                              /> 
                            <span >{{$option->o_value}}</span> 

                            </label>
                            @endforeach
                        </div>
                    </div> 
                </div>
            </div>
            @else
            <div  class="ivu-col ivu-col-span-18">
                <h4 >[填空] @{{question.content}} <span  style="color: rgb(255, 0, 0);">*</span>
                </h4> 
            
                <div  class="question-options">
                    <div  class="option-item">
                        <div  class="ivu-input-wrapper ivu-input-type">
                        <textarea placeholder="请输入..." disabled="disabled" rows="2" class="ivu-input ivu-input-disabled" style="height: 52px; min-height: 52px; max-height: 115px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            @endif
       
        </div>
    @endforeach
  </section>
</div>
@endsection

@section('js')
  <script src="/js/backend-tables.js"></script>
  <script src="/vendor/bootstrap-wysihtml/bootstrap3-wysihtml5.all.min.js"></script>
  <script>
    var data = {
        naire:{
            nid:'{{$naire->id}}',
            title:'{{$naire->title}}',
            description:'{{$naire->discreption}}'
        },
        questions: [
            @foreach  ($questions as $question)
            {
                qid:'{{$question->id}}',
                type: '{{$question->q_type}}',
                content:'{!!str_replace("'","\'",$question->content)!!}',
                required:'{{$question->isrequired}}',
                options:[
                    @foreach  ($question->options as $option)
                    {
                        oid:'{{$option->id}}',  
                        value:'{!!str_replace("'","\'",$option->o_value)!!}',  
                      
                    },
                    @endforeach
                ]
            },
            @endforeach
        ],
        newQuestion : {
            qid:'',
            type:'单选',
            content:'',
            options:[{
                        name:"",
                        title:"选项",
                        holder:"请输入选项内容",
                        type:'input',
                    }]
        },
        alert:alert,
      }

  </script>

<script>

        var quesions = new Vue({
            el: 'body',
            data: data,
            methods: {
                set: function (data) {
                    tables.modal_data = data.log_data;
                },
 
            }
        });

    </script>
@endsection