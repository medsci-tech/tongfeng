@extends('backend.layouts.app')

@section('title','问卷编辑')

@section('css')

<link rel="stylesheet" href="/css/question.css">
<style>
    .option_count {
        font-size: 15px;
        font-weight: 700;
        color: red;
        padding-left: 20px;
    }
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>
        问卷结果统计
      </h1>
    </section>
    <section class="content">
    <a href="/admin/naire/summary" style="float:right;" class="btn btn-large btn-info">返回 </a>
    <div v-for="question in questions" style="background-color:white;margin-top: 2px;" >

        <div  class="question-item ivu-row-flex ivu-row-flex-top ivu-row-flex-start">
            <div  class="ivu-col ivu-col-span-6" style="width: 60px; text-align: center;">
                <h4 >Q@{{$index+1}}:</h4> 
            </div> 
        <template v-if="question.type=='单选'">
 
            <div  class="ivu-col ivu-col-span-18">
                <h4 >[单选] @{{question.content}} <span  style="color: rgb(255, 0, 0);">*</span>
                </h4> 
                <!----> 
                <div  class="question-options">
                    <div >
                        <div  class="ivu-radio-group ivu-radio-group-vertical" style="width: 100%;">
                       
                            
                            <label v-for='option in question.options'  class="option-item ivu-radio-wrapper ivu-radio-group-item ivu-radio-wrapper-disabled">
                                <span class="ivu-radio ivu-radio-disabled"><span class="ivu-radio-inner"></span> 
                                <input type="radio" disabled="disabled" class="ivu-radio-input" /></span>
                                <span >@{{option.value}}</span> 
                                <span class="option_count">( @{{option.count}} )</span>

                            </label>
                        </div>
                    </div>
                </div>
            </div>
        
        </template>
        <template v-if="question.type=='多选'">
      
            <div  class="ivu-col ivu-col-span-18">
                <h4 >[多选] @{{question.content}} <span  style="color: rgb(255, 0, 0);">*</span>
                </h4> 
                <!----> 
                <div  class="question-options">
                <!----> 
                    <div >
                        <div  class="checkbox-list ivu-checkbox-group">
                            <label v-for='option in question.options'  class="option-item ivu-checkbox-wrapper ivu-checkbox-group-item ivu-checkbox-wrapper-disabled"><span class="ivu-checkbox ivu-checkbox-disabled"><span class="ivu-checkbox-inner"></span> <input type="checkbox" disabled="disabled" class="ivu-checkbox-input" value="" /> 
                                <!----></span> <span >@{{option.value}}</span> 

                                <span class="option_count">( @{{option.count}} )</span>

                            </label>
                        
                        </div>
                    </div> 
                </div>
            </div>
        
        </template>
        </div>

    </div>
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
                        count:'{{ array_key_exists($option->id,$result)? $result[$option->id] :0  }}'
                    },
                    @endforeach
                ]
            },
            @endforeach
        ],
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
                show: function (e) {
                    $('#modal-list').modal('show');
                }
            }
        });

    </script>
@endsection