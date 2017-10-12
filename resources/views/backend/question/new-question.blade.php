<div v-cloak class="modal" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <style>
    .iv-btn {
      display: inline-block;
      margin-bottom: 0;
      font-weight: 400;
      text-align: center;
      vertical-align: middle;
      -ms-touch-action: manipulation;
      touch-action: manipulation;
      cursor: pointer;
      background-image: none;
      border: 1px solid transparent;
      white-space: nowrap;
      line-height: 1.5;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      padding: 6px 15px;
      font-size: 12px;
      border-radius: 4px;
      transform: translateZ(0);
      transition: color .2s linear,background-color .2s linear,border .2s linear;
      color: #657180;
      background-color: #f7f7f7;
      border-color: #d7dde4;
    }
    .iv-btn-success {
      color: #fff;
      background-color: #0c6;
      border-color: #0c6;
    }
    .iv-btn-warning {
      color: #fff;
      background-color: #f90;
      border-color: #f90;
    }
    .iv-btn-small {
      padding: 2px 5px
    }
    .input-small {
      hight: 30px
    }
  </style>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加@{{newQuestion.type}}题</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" @submit.prevent='confirmQuestionEdit'>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input  type="hidden" name="_method" value="put"/>
          <div class="form-group">
             <label  class="control-label" style="width:80px;float:left;">题目</label>
            
            <div class="col-sm-8" style="float:left;">
                <input type="text" required class="form-control col-sm-8 input-small"  v-model="newQuestion.content" placeholder="请输入题目内容">
            </div>
          </div>
          <div v-if="newQuestion.type!=='填空'" v-for="data in newQuestion.options">
            <div class="form-group">
              <label  class="control-label" style="width:80px;float:left;">
                <span v-if="$index==0">@{{ data.title }}</span>
              </label>
              <div class="col-sm-8" style="float:left;">
                <input type="@{{ data.type }}" required class="form-control col-sm-8 input-small" name="@{{ data.name }}" id="@{{ data.name }}" v-model="data.value" placeholder="@{{data.holder}}">
              </div>
              <div class="option-btn " style="display:inline-block;float:left;">
                <button type="button" class="iv-btn iv-btn-success iv-btn-small " @click="addOption">
                  <i class="glyphicon glyphicon-plus"></i> 
                </button> 
                <button v-if="newQuestion.options.length!==1" type="button" class="iv-btn iv-btn-warning iv-btn-small " @click="removeOption($index)">
                  <i class="glyphicon glyphicon-remove"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            <button type="submit" class="btn btn-primary">确认</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

