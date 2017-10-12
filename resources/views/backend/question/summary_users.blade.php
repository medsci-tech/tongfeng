@extends('backend.layouts.app')

@section('title','问卷调查')
@section('box_title','投票用户')

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
                    @{{ item | text }}
                </td>
                <td style="white-space: nowrap">
                  <div>
                    <button class="btn btn-xs btn-primary" @click='voteDetail({{$naire->id}},data[0])'>投票详情</button>
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
        table_head: ['id', '姓名', '电话号码'],
        table_data: [
          @foreach($students as $student)
            ['{{$student->id}}', '{!!str_replace("'","\'",$student->name)!!}', '{!!str_replace("'","\'",$student->phone)!!}'],
          @endforeach
        ],
        pagination: '{{$students->render() }}',
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
        voteDetail: function (nid,sid) {
          window.open('/admin/naire/summary/user/' + nid + '/' + sid ,'_self');
        },
      }
    });


  </script>
@endsection