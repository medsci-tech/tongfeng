@extends('backend.tables.index')

@section('title','问卷调查')
@section('box_title','问卷管理')
   
@section('tables_data')
  <script>
    var data = {
        table_head: ['id', '标题', '简单描述','激活状态','创建日期'],
        table_data: [
          @foreach($nairs as $nair)
            ['{{$nair->id}}', '{!!str_replace("'","\'",$nair->title)!!}', '{!!str_replace("'","\'",$nair->description)!!}', '{{$nair->n_status==1?'激活':'未激活'}}','{{$nair->created_at}}'],
          @endforeach
        ],
        pagination: '{{$nairs->render() }}',
        modal_data: [
            {
              box_type: 'input',
              name: 'id',
              type: 'text'
            },
            {
              box_type: 'input',
              name: 'title',
              type: 'text'
            },
            {
              box_type: 'textarea',
              name: 'description',
              row: 8
            },
            {
              box_type: 'select',
              name: 'n_status',
              option: {
                '激活': '1',
                '未激活': '0'
              },
              defaultValue: '0'
            },
            
            {
              box_type: 'hide',
              name: 'logo_url',
              type: 'text'
            }
          ],
  
          update_info: {
            title: '编辑',
            action: '/admin/naire/naire',
            method: 'put'
          },
          add_info: {
            title: '添加',
            action: '/admin/naire/naire',
            method: 'post'
          },
          delete_info: {
            url: '/admin/naire/naire',
            method: 'delete'
          },
  
          form_info: {
            title: '编辑',
            action: '',
            method: 'post'
          },
          alert: alert,
        }

    function edit_question(obj){
        var id= $(obj).parent().parent().find('td:first').text();
        id = id.replace(/(^\s*)/g,'').replace(/(\s*$)/g,'');
        window.open('/admin/naire/editquestion/' + id ,'_self');
    }
  </script>
@endsection

@section('others_button')
<button class="btn btn-xs btn-primary" onclick='edit_question(this);'>编辑问题</button>
@endsection