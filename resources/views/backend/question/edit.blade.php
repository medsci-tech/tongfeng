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
        编辑问卷问题
      </h1>
    </section>
    <section class="content">
    <div>
        <button class="btn btn-large btn-primary" @click="addQuestion({{$naire->id}},'单选')">单选题</button>
        <button class="btn btn-large btn-primary" @click="addQuestion({{$naire->id}},'多选')">多选题</button>
        <button class="btn btn-large btn-primary" @click="addQuestion({{$naire->id}},'填空')">文本题</button>

        <a href="/admin/naire" style="float:right;" class="btn btn-large btn-info">返回 </a>
    </div>
    <div v-for="question in questions" style="background-color:white;margin-top: 2px;" >

        <div  class="question-item ivu-row-flex ivu-row-flex-top ivu-row-flex-start">
            <div  class="ivu-col ivu-col-span-6" style="width: 60px; text-align: center;margin: 5px 0;">
                <h4 >Q@{{$index+1}}:</h4> 
            </div> 
        <template v-if="question.type=='单选'">
 
            <div  class="ivu-col ivu-col-span-18">
                <h4 >[单选] @{{question.content}} <span  style="color: rgb(255, 0, 0);">*</span>
                <button type="button" class="iv-btn iv-btn-success iv-btn-small " @click="editQuestion($index)">
                  <i class="glyphicon glyphicon-edit"></i> 
                </button>
                <button type="button" class="iv-btn iv-btn-warning iv-btn-small " @click="deleteQuestion($index)">
                  <i class="glyphicon glyphicon-remove"></i>
                </button>
                </h4> 
                <!----> 
                <div  class="question-options">
                    <div >
                        <div  class="ivu-radio-group ivu-radio-group-vertical" style="width: 100%;">
                       
                            
                            <label v-for='option in question.options'  class="option-item ivu-radio-wrapper ivu-radio-group-item ivu-radio-wrapper-disabled">
                                <span class="ivu-radio ivu-radio-disabled"><span class="ivu-radio-inner"></span> 
                                <input type="radio" disabled="disabled" class="ivu-radio-input" /></span>
                                <span >@{{option.value}}</span> 
                                <!----> 
                                <div  class="option-action">
                                    <i  class="ivu-icon ivu-icon-close" style="font-size: 16px;"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        
        </template>
        <template v-if="question.type=='多选'">
      
            <div  class="ivu-col ivu-col-span-18">
                <h4 >[多选] @{{question.content}} <span  style="color: rgb(255, 0, 0);">*</span>
                    <button type="button" class="iv-btn iv-btn-success iv-btn-small " @click="editQuestion($index)">
                    <i class="glyphicon glyphicon-edit"></i> 
                    </button>
                    <button type="button" class="iv-btn iv-btn-warning iv-btn-small " @click="deleteQuestion($index)">
                        <i class="glyphicon glyphicon-remove"></i>
                    </button>
                </h4> 
                <!----> 
                <div  class="question-options">
                <!----> 
                    <div >
                        <div  class="checkbox-list ivu-checkbox-group">
                            <label v-for='option in question.options'  class="option-item ivu-checkbox-wrapper ivu-checkbox-group-item ivu-checkbox-wrapper-disabled"><span class="ivu-checkbox ivu-checkbox-disabled"><span class="ivu-checkbox-inner"></span> <input type="checkbox" disabled="disabled" class="ivu-checkbox-input" value="" /> 
                                <!----></span> <span >@{{option.value}}</span> 
                                <div  class="option-action">
                                <i  class="ivu-icon ivu-icon-close" style="font-size: 16px;"></i>
                                </div>
                            </label>
                        
                        </div>
                    </div> 
                </div>
            </div>
        
        </template>
        <template v-if="question.type=='填空'">
       
            <div  class="ivu-col ivu-col-span-18">
                <h4 >[填空] @{{question.content}} <span  style="color: rgb(255, 0, 0);">*</span>
                    <button type="button" class="iv-btn iv-btn-success iv-btn-small " @click="editQuestion($index)">
                    <i class="glyphicon glyphicon-edit"></i> 
                    </button>
                    <button type="button" class="iv-btn iv-btn-warning iv-btn-small " @click="deleteQuestion($index)">
                        <i class="glyphicon glyphicon-remove"></i>
                    </button>
                </h4> 
            
                <div  class="question-options">
                    <div  class="option-item">
                        <div  class="ivu-input-wrapper ivu-input-type">
                        <textarea placeholder="请输入..." disabled="disabled" rows="2" class="ivu-input ivu-input-disabled" style="height: 52px; min-height: 52px; max-height: 115px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        
        </template>
        </div>

    </div>
  </section>
</div>
@include('backend.question.new-question')
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
                show: function (e) {
                    $('#modal-list').modal('show');
                },
                addQuestion: function (nid,type) {
                    data.newQuestion={
                        nid:nid,
                        qid:'',
                        type:type,
                        content:'',
                        options:[]
                    };
                    if (type !='填空'){
                        this.addOption();
                    }
                    $('#modal-edit').modal('show');
                },
                deleteQuestion: function(index){
                    
                    var question = data.questions[index];
                    if (question.qid==''){
                        data.questions.splice(index,1);
                        return;
                    }
                    $.post('/admin/naire/question/' + question.qid, {_method:'DELETE'}, function (resp) {
                        data.questions.splice(index,1);
                    })
                },
                editQuestion: function(index){
                    var ques = JSON.parse(JSON.stringify(data.questions[index]));
                    data.newQuestion = ques;
                    $('#modal-edit').modal('show');
                },
                addOption: function () {
                    data.newQuestion.options.push({
                        name:"",
                        title:"",
                        holder:"请输入选项内容",
                        type:'input',
                    });
                },
                removeOption: function (index){
                    data.newQuestion.options.splice(index,1);
                },
                confirmQuestionEdit: function (){
                    var isEdit = data.newQuestion.qid!='';
                    var ques = JSON.parse(JSON.stringify(data.newQuestion));
                    ques.nid= data.naire.nid;
                    $.post('/admin/naire/question/save', ques, function (resp) {
                        if (!isEdit){
                          data.questions.push(ques);
                        }else{
                            var newArr = data.questions.filter(function(item){
                                return item.qid === data.newQuestion.qid;
                            });
                            if (newArr.length!=0){
                                var q = newArr[0];
                                q.type= ques.type;
                                q.content= ques.content;
                                q.options= ques.options;
                            }
                        }
                    })
 
                    
                    $('#modal-edit').modal('hide');
                }
            }
        });

    </script>
@endsection