@extends('backend.layouts.app')

@section('title','问卷调查')
@section('box_title','问卷统计')

@section('content')
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@yield('box_title')</h3>
              <div class="box-tools">
                <div class="input-group" style="width: 150px;">
                  <input name="table_search" class="form-control input-sm pull-right" disabled placeholder="搜索暂不可用"
                         type="text">
                  <div class="input-group-btn">
                    <button class="btn btn-sm btn-default" disabled><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div><!-- /.box-header -->
            <div class="box-body no-padding" style="overflow: auto" v-cloak>
              <table class="table table-bordered table-hover table-striped table-responsive">
                <thead style="word-break: keep-all">
                <tr role="row">
                  <th v-for="head in table_head" rowspan="1" colspan="1" style="white-space: nowrap">@{{ head }}</th>
                  <th rowspan="1" colspan="1" style="white-space: nowrap">
                    操作&emsp;
                  </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="data in table_data">
                <td v-for="item in data" track-by="$index">
                  <div v-if="is_img(item)">
                    <img class="img-responsive" :src="item" alt="">
                  </div>
                  <div v-else>
                    @{{ item | text }}
                  </div>
                </td>
                <td style="white-space: nowrap">
                  <div>
                    <button class="btn btn-xs btn-primary" @click='summary_detail(data[0])'>问卷统计</button>
                    <button class="btn btn-xs btn-primary" @click='naireUsers(data[0])'>投票用户</button>
                  </div>
                </td>
                </tr>
                </tbody>
              </table>

            </div><!-- /.box-body -->
            <div v-cloak class="box-footer clearfix">
              @{{{ pagination }}}
            </div>
          </div><!-- /.box -->
        </div><!-- /.col -->
      </div>

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
@endsection
   
@section('js')
  <script src="/js/backend-tables.js"></script>
  <script src="/vendor/bootstrap-wysihtml/bootstrap3-wysihtml5.all.min.js"></script>
  <script>
    var data = {
        table_head: ['id', '标题', '简单描述','投票人数','创建日期'],
        table_data: [
          @foreach($nairs as $nair)
            ['{{$nair->id}}', '{!!str_replace("'","\'",$nair->title)!!}', '{!!str_replace("'","\'",$nair->description)!!}','{{ array_key_exists($nair->id,$users)? $users[$nair->id]:0}}','{{$nair->created_at}}'],
          @endforeach
        ],
        pagination: '{{$nairs->render() }}',
        form_info: {
          title: '编辑',
          action: '',
          method: 'post'
        },
        alert: alert,
    }

  </script>



  <script>

    Vue.filter('text', function (value) {
      return value.replace(/<\/?[^>]*>/g,'');
    });

    var tables = new Vue({
      el: 'body',
      compiled: function () {
        var l = this.table_head.length;

      },
      data: data,
      methods: {
        summary_detail: function (id) {
          window.open('/admin/naire/summary/detail/' + id ,'_self');
        },
        naireUsers: function(id){
          window.open('/admin/naire/summary/users/' + id ,'_self');
        },
      }
    });


  </script>
@endsection