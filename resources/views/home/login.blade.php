@extends('layouts.open')

@section('title','登录')

@section('page_id','login')

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

            <form action="/home/login" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="row column log-in-form">
                    <h4 class="text-center">Mime账号登录</h4>
                    <label>手机号
                        <input type="text" placeholder="请输入您的手机号" name="phone">
                    </label>
                    <label>密码
                        <input type="password" placeholder="请输入密码" name="password">
                    </label>
                    @if (count($errors) > 0)
                        <p id="error" class="help-text">您输入的手机号码或者密码有误</p>
                    @endif
                    <input id="remember-me" type="checkbox"><label for="remember-me">记住我</label>

                    <p>
                        <button type="submit" class="button expanded">登&emsp;录</button>
                    </p>
                    <p class="clearfix">
                        <a class="forget-pwd-link" href="/home/pwd2back_view">忘记密码</a>
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
            el: '#login',
            data: {}
        });
    </script>
@endsection