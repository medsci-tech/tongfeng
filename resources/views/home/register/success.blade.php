<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>报名成功</title>
<link rel="stylesheet" type="text/css" href="/css/page.css">
</head>

<body>
<div class="dialog-wrapper">
    <div class="dialog dialog_1 noborder">
        <div class="dialog-heading">
            <span class="with-icon-right">报名成功，点击<a class="txt-danger" href="/">返回首页</a></span>
        </div>
        <div class="dialog-body">
         @if (Carbon\Carbon::now()->lt(Carbon\Carbon::parse('2017-09-07')))
            <p class="txt-danger">本课程9月7号正式开课，敬请期待</p>
         @endif
            <p class="qrcode-wrapper">
                <img src="/image/qrcode.png">
            </p>
        </div>
    </div>
</div>
</body>
</html>