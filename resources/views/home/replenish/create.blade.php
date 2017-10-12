@extends('layouts.open')

@section('title','完善个人信息')

@section('page_id','personal')

@section('css')
    <style>
        .log-in-form {
            /*border: 1px solid #cacaca;*/
            padding: 1rem 1rem !important;
            border-radius: 3px;
        }

        .help-text {
            color: #ec5840;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="medium-6 medium-centered large-4 large-centered columns">
            <br>

            <form action="/home/replenish/store" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="row column log-in-form">
                    <h4 class="text-center">个人信息完善</h4>

                    <label>姓名
                        <input required v-model="name" type="text" placeholder="请输入" name="name">
                    </label>

                    @if($errors->has('name'))
                        <p class="help-text">{{ $errors->first('name')}}</p>
                    @endif

                    <label>昵称
                        <input required v-model="nickname" type="text" placeholder="请输入" name="nickname">
                    </label>

                    @if($errors->has('nickname'))
                        <p class="help-text">{{ $errors->first('nickname')}}</p>
                    @endif

                    <div style="font-size: .875rem">
                        <div class="small-2 columns" style="padding-left: 0;">性别</div>
                        <label class="small-5 columns">
                            <input v-model="sex" type="radio" value="1" name="sex">男
                        </label>
                        <label class="small-5 columns">
                            <input v-model="sex" type="radio" value="0" name="sex">女
                        </label>
                    </div>
                    @if($errors->has('sex'))
                        <p class="help-text">{{ $errors->first('sex')}}</p>
                    @endif

                    <label>出生日期
                        <input required v-model="birthday" type="date" placeholder="选择出生日期" name="birthday">
                    </label>
                    @if($errors->has('birthday'))
                        <p class="help-text">{{ $errors->first('birthday')}}</p>
                    @endif

                    @if($errors->has('title'))
                        <p class="help-text">{{ $errors->first('title')}}</p>
                    @endif

                    <label>邮箱
                        <input required v-model="email" type="email" placeholder="请输入" name="email"
                               value="{{old('email')}}">
                    </label>

                    @if($errors->has('email'))
                        <p class="help-text">{{ $errors->first('email')}}</p>
                    @endif

                    <p>
                        <button type="submit" class="button expanded">确&emsp;认</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <br>
@endsection


@section('js')
    <script src="/vendor/city.js/city.js"></script>
    <script>
        vm = new Vue({
            el: '#personal',
            data: {
                name: '{{old('name') ? old('name') : $student->name}}',
                nickname: '{{old('nickname') ? old('nickname') : $student->nickname}}',
                sex: '{{old('sex') ? old('sex') : $student->sex ?$student->sex :1}}',
                birthday: '{{old('birthday') ? old('birthday') : $student->birthday}}',
                province: '{{old('province') ? old('province') : $student->province}}',
                city: '{{old('city') ? old('city') : $student->city}}',
                area: '{{old('area') ? old('area') : $student->area}}',
                hospital_name: '{{old('hospital_name') ? old('hospital_name') : $student->hospital_name}}',
                office: '{{old('office') ? old('office') : $student->office}}',
                title: '{{old('title') ? old('title') : $student->title}}',
                email: '{{old('email') ? old('email') : $student->email}}',
                office_array: [
                    '妇产科'
                    , '核医学科'
                    , '甲状腺外科'
                    , '老年科'
                    , '内分泌科'
                    , '其他科室'
                    , '全科'
                    , '神经科'
                    , '肾内科'
                    , '心血管科'
                    , '肿瘤科'
                    , '综合内科'
                ],
                title_array: [
                    '副主任医师'
                    , '主任医师'
                    , '主治医师'
                    , '住院医师'
                ]
            }
        });

        $(function () {
            city_selector();

            if (vm.province != '') {
                $('#province').val(vm.province);
                $('#province').trigger('change');
            }
            if (vm.city != '') {
                $('#city').val(vm.city);
                $('#city').trigger('change');
            }
            if (vm.area != '') {
                $('#area').val(vm.area);
                $('#area').trigger('change');
            }
            if (vm.office != '') {
                $('#office').val(vm.office);
                $('#office').trigger('change');
            }
            if (vm.title != '') {
                $('#title').val(vm.title);
                $('#title').trigger('change');
            }

        });
    </script>
@endsection