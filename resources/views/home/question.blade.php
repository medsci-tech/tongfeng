@extends('layouts.open')

@section('title','问卷调查')

@section('page_id','personal')

@section('css')
<link rel="stylesheet" href="/css/question.css">
<link rel="stylesheet" href="/css/page.css">
<style>
h1#naire_title {
    text-align: center;
}
div#naire_desc {
    border-bottom: solid;
    text-indent: 30px;
}
label.option-item.ivu-radio-wrapper.ivu-radio-group-item {
    font-size: 12px;
}
.question_wrapper{
    margin: 3px auto;
}
.content h1, .content h2, .content h3, .content h4,.content h5, .content h6 {
     background-color: #fff; 
}
</style>
@endsection
@section('content')
<div class="content">
<div class="dialog dialog_2">


    <h1 id="naire_title">{{$naire->title}}</h1>
    <div id="naire_desc">{!!$naire->description!!}</div>

    <form class="form-horizontal" role="form" @submit.prevent='confirmQuestionEdit'>
        <div v-for="question in questions" class="question_wrapper" >
            <div  class="question-item ivu-row-flex ivu-row-flex-top ivu-row-flex-start">
                <div  class="ivu-col ivu-col-span-6" style="width: 60px; text-align: center">
                    <h4 >Q@{{$index+1}}:</h4> 
                </div> 
            <template v-if="question.type=='单选'">
    
                <div  class="ivu-col ivu-col-span-18">
                    <h4 >[单选] @{{question.content}} <span  style="color: rgb(255, 0, 0);">*</span>
                    <div  class="question-options">
                        <div  class="ivu-radio-group ivu-radio-group-vertical" style="width: 100%;">
                            <label v-for='option in question.options' class="option-item ivu-radio-wrapper ivu-radio-group-item">
                                <input type="radio" required value="@{{option.oid}}" name="@{{question.qid}}" v-model="question.selected">@{{option.value}}
                            </label>
                        </div>
                    </div>
                </div>
            
            </template>
            <template v-if="question.type=='多选'">
        
                <div  class="ivu-col ivu-col-span-18">
                    <h4 >[多选] @{{question.content}}<span  style="color: rgb(255, 0, 0);">*</span>
                    </h4> 
                    <div  class="question-options">
                            <div  class="checkbox-list ivu-checkbox-group">
                                <label v-for='option in question.options'  class="option-item ivu-checkbox-wrapper ivu-checkbox-group-item ">
                                    <input type="checkbox" required value="@{{option.oid}}" name="@{{question.qid}}" v-model="question.checked" @change="selectedChange(question.qid)">@{{option.value}}
                                </label>
                            </div>
                    </div>
                </div>
            
            </template>
            <template v-if="question.type=='填空'">
        
                <div  class="ivu-col ivu-col-span-18">
                    <h4 >[填空] @{{question.content}} <span  style="color: rgb(255, 0, 0);">*</span>
                    </h4> 
                
                    <div  class="question-options">
                        <div  class="option-item">
                            <div  class="ivu-input-wrapper ivu-input-type">
                            <textarea placeholder="请输入..." required rows="4" class="ivu-input ivu-input-disabled" style="height: 52px; min-height: 52px; max-height: 115px;" v-model="question.text"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            
            </template>
            </div>

        </div>
        <div class="modal-footer">
            <button type="submit" class="button expanded">提&emsp;交</button>
        </div>
    </form>
</div>
</div>

@endsection

@section('js')

  <script src="/vendor/bootstrap-wysihtml/bootstrap3-wysihtml5.all.min.js"></script>
  <script>
     $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':'{{csrf_token()}}'
        }
    });
    var data = {
        naire:{
            nid:'{{$naire->id}}',
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
                ],
                selected:'',
                checked:[],
                text:'',
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
                selectedChange: function (qid){
                    var checkboxes = $(':checkbox[name="'+qid+'"]');
                    var newArr = data.questions.filter(function(item){
                                return item.qid === qid;
                    });
                    var q = newArr[0];
                    if (q.checked.length>0){
                        checkboxes.removeAttr("required");
                    }else{
                        checkboxes.attr("required","required");
                    }
                },
                confirmQuestionEdit: function (){
                    var answerArr=[];
                    for (var i=0;i<data.questions.length;i++){
                        var qid = data.questions[i].qid;
                        var type = data.questions[i].type;
                        var choices = [];
                        if (type=='单选'){
                            choices = [data.questions[i].selected];
                        }else if (type=='多选'){
                            choices = data.questions[i].checked;
                        }
                        var text = data.questions[i].text;

                        answerArr.push({
                            qid: qid,
                            type:type,
                            choices:choices,
                            text:text
                        });
                    }
                    $.ajax({
                        type:'POST',
                        url:'/home/question/answer/' + data.naire.nid , 
                        data: JSON.stringify(answerArr), 
                        processData: false,
                        success:function (resp) {
                            window.open('/home/question/success/answer','_self');
                        }
                    })

                }
            }
        });

    </script>
@endsection