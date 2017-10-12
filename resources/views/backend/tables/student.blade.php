@extends('backend.tables.index_student')

@section('title', '学生信息')
@section('box_title','学生列表')


@section('tables_data')
  <script>
    var data = {
      table_head: ['id', '手机号', '邮箱', '姓名','性别','出生日期', '省', '市', '区', '医院', '医院级别' ,'科室', '职称', '二维码注册', '报名时间'],
      log_head: ['课程名称', '点击时间', '观看时长'],
      data: [
        @foreach($students as $student)
        {
          @php
            $sex = $student->sex==0? '女':'男';
          @endphp

          table_data: ['{{$student->id}}', '{{$student->phone}}', '{{$student->email}}','{{$student->name}}','{{$sex}}','{{$student->birthday}}', '{{$student->province}}', '{{$student->city}}', '{{$student->area}}', '{{$student->hospital_name}}','{{$student->hospital_level}}', '{{$student->office}}', '{{$student->title}}', '{{empty($student->promo_code)?'否':'是'}}', '{{$student->entered_at}}'],
          log_data: [
            @foreach($student->playLogs as $log)
              @foreach($log->details as $key => $value)
                ['{{$courseArray[$log->thyroid_class_course_id]}}', '{{$key}}', '{{$value}}'],
              @endforeach
            @endforeach
          ]
        },
        @endforeach
      ],
      modal_data: '',
      alert: alert,
      @php
        if (isset($search)){
          $students->appends(['search' => $search]);
        }
        if (isset($filterByPromo) && $filterByPromo!=0){
          $students->appends(['filterByPromo' => 'on']);
        }
      @endphp

      pagination: '{{$students->render() }}'


    }
  </script>
@endsection