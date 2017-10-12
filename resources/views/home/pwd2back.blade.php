@extends('layouts.open')

@section('title','登录')

@section('page_id','pwd2back')

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
        .register-link,.forget-pwd-link{
            display: inline-block;
            width: 50%;
        }
        /*.register-link:hover,.forget-pwd-link:hover{*/
        /*text-decoration: underline;*/
        /*}*/
        .register-link{
            float: right;
            text-align: right;
        }
        .forget-pwd-link{
            float: left;
            text-align: left;
        }

        /*清除浮动*/
        .clearfix:before,.clearfix:after{
            content:"";
            display:table;
        }
        .clearfix:after{
            clear:both;
        }
        .clearfix{
            *zoom:1;/*IE/7/6*/
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="medium-6 medium-centered large-4 large-centered columns">
            <br>

            <form action="/home/pwd2back_post" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="row column log-in-form">
                    <h4 class="text-center">Mime账号登录</h4>
                    <label>手机号
                        <input required v-model="phone" type="text" value="{{ old('phone') }}" placeholder="请输入您的手机号" name="phone">
                    </label>
                    <p id="error_phone" class="help-text hide">请输入正确的手机号!</p>
                    @if($errors->has('phone'))
                        <p class="help-text">{{ $errors->first('phone')}}</p>
                    @endif
                    <label>验证码
                        <div class="input-group">
                            <input required v-model="sms" class="input-group-field" type="text" placeholder="请输入验证码"
                                   name="auth_code">

                            <div class="input-group-button">
                                <button @click="get_auth_code" type="button" class="button">获取验证码</button>
                            </div>
                        </div>
                    </label>

                    @if($errors->has('auth_code'))
                        <p class="help-text">{{ $errors->first('auth_code')}}</p>
                    @endif
                    <label>密码
                        <input required v-model="password" type="password" placeholder="6-16位，区分大小写，不可用特殊符号" name="password">
                    </label>
                    @if($errors->has('password'))
                        <p class="help-text">{{ $errors->first('password')}}</p>
                    @endif
                    <label>确认密码
                        <input required v-model="password_confirmation" type="password" placeholder="请再次输入密码" name="password_confirmation">
                    </label>
                    @if($errors->has('password_confirmation'))
                        <p class="help-text">{{ $errors->first('password_confirmation')}}</p>
                    @endif
                    <p v-show="is_same" class="help-text">两次输入的密码不一致!</p>

                    <p>
                        <button type="submit" class="button expanded">确定</button>
                    </p>
                    <p class="clearfix">
                        <a class="forget-pwd-link" href="/home/login">已有账号?立即登陆</a>
                        <a class="register-link" href="/home/register/create">没有账号?点击注册</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('js')
    <script>
        vm = new Vue({
            el: '#pwd2back',
            data: {
                phone: '',
                sms: '',
                password: '',
                password_confirmation: '',
            },
            methods: {
                get_auth_code: function () {

                    $('#error_phone').addClass('hide');
                    $('#error_phone').text('请输入正确的手机号!');
                    $('.input-group-button button').attr("disabled", "disabled");

                    var myreg = /^(((12[0-9]{1})|(13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                    if (myreg.test(vm.phone)) {

                        var i = 61;
                        timer();
                        function timer() {
                            i--;
                            $('.input-group-button button').text(i + '秒后重发');
                            if (i == 0) {
                                clearTimeout(timer);
                                $('.input-group-button button').removeAttr("disabled");
                                $('.input-group-button button').text('重新发送');
                            } else {
                                setTimeout(timer, 1000);
                            }
                        }

                        $.get('/home/send_sms', {phone: vm.phone}, function (data) {
                                if (data.success) {
                                } else {
                                    $('#error_phone').text(data.error_message);
                                    $('#error_phone').removeClass('hide');
                                }
                            }
                        );
                    } else {
                        $('#error_phone').removeClass('hide');
                        $('.input-group-button button').removeAttr("disabled");
                    }
                }
            },
            computed: {
                is_same: function () {
                    if(this.phone === ''||this.sms === ''|| this.password === ''||this.password_confirmation === ''||this.agree === false||this.password != this.password_confirmation){
                        $("button[type='submit']").attr("disabled", "disabled")
                    }else{
                        var myreg = /^(((12[0-9]{1})|(13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                        if (myreg.test(vm.phone)) {
                            $('#error_phone').addClass('hide');
                            $("button[type='submit']").removeAttr("disabled");
                        }
                        else {
                            $('#error_phone').text('请输入正确的手机号!');
                            $('#error_phone').removeClass('hide');
                            $("button[type='submit']").attr("disabled", "disabled");
                        }
                    }
                    return (this.password === this.password_confirmation)?false:true
                }
            }
        });
    </script>
@endsection