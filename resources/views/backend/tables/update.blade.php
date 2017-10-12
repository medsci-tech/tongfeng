@extends('backend.layouts.app')

@section('title','tables')

@section('css')
  <link rel="stylesheet" href="/css/backend-tables.css">
  <link rel="stylesheet" href="/vendor/bootstrap-wysihtml/bootstrap3-wysihtml5.css">
  <style>
    .table .success td, .table .success th {
      background-color: #dff0d8 !important;
    }

    table.dataTable.display tbody tr.success > .sorting_1,
    table.dataTable.order-column.stripe tbody tr.success > .sorting_1 {
      background-color: #d9ead4 !important;
    }

    @media (max-width: 767px) {
      .fixed .content-wrapper, .fixed .right-side {
        padding-top: 50px;
      }
    }

  </style>
@endsection

@section('content')
  <div class="content-wrapper">


    <!-- Content Header (Page header) -->
    <section class="content-header">
      @include('backend.layouts.alerts')
      <h1>
        @yield('title')
      </h1>
      {{--<ol class="breadcrumb">--}}
      {{--<li><a href="#"><i class="fa fa-dashboard"></i>主页</a></li>--}}
      {{--<li class="active">列表</li>--}}
      {{--</ol>--}}
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">导入文件更新学员信息</h3>
              {{--<a class="btn btn-xs btn-success" href="/admin/excel/export-student">导出</a>--}}

              <div class="form">
                <form id="search_form" action="" method="post" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="input-group" style="width: 400px;">
                    <input name="file" class="form-control" type="file">

                    <div class="input-group-btn">
                      <button class="btn btn-default" type="submit">提交</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>

    </section>
    <!-- /.content -->
  </div><!-- /.content-wrapper -->
@endsection
