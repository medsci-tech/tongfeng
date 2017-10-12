<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Document</title>

<style>
    html{
    font-family: sans-serif, "Microsoft YaHei UI", "Microsoft YaHei";
    font-size: 15px;
    margin: 0;
    padding: 0;
}
body{
    margin: 0;
    padding: 0;
}
.dialog-wrapper{
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999999;
}
.dialog{
    margin: 4% auto;
    min-width: 40%;
    min-height: 320px;
    max-width: 100%;
    border: 1px solid #666;
    padding: 16px auto;
    background: #fff;
}
.dialog.border{
    border: 1px solid #666;
}
.dialog .dialog-heading{
    font-size: 1.75rem;
}
.dialog .dialog-body{
    font-size: 1.5rem;
}

.txt-danger{
    color: red;
}

.qrcode-wrapper{
    text-align: center;
}
.qrcode-wrapper img{
    width: 80%;
}

.dialog_1{
    width: 600px;
}
.dialog_1 .dialog-body{
    text-align: center;
}
.dialog_1 .dialog-heading{
    height: 70px;
    line-height: 70px;
    text-align: center;
}

@media screen and (max-width: 480px){
    .dialog-wrapper{
        position: static;
    }
    .dialog.border{
        border: 0 none;
    }
    .dialog-wrapper .dialog{
        width: 100%;
    }
    .dialog_1 .dialog-heading{
        font-size: 1.5rem;
    }
    .dialog_1 .dialog-body{
        font-size: 1rem;
    }
    .dialog_1 .dialog-heading{
        transform: scale(0.75)
    }
}
    .dialog-heading1 {
        margin-left:10px;
    }
    .dialog-body1 {
        position: relative;
        background-image: url("/image/bg_register_sucess.png");
        background-repeat: no-repeat;
        background-size: 100%;
        display: inline-block;
        content: "";  
        width: 100%;
        padding-top: 16%;
        padding-bottom:10px;
        color:#fff;
    }

    .dialog-body1 .desc {
        width: 50%;
        margin-top: 0;
        margin-left: 10px;
        padding-bottom: 70px;
    }
    .desc p{
        text-align: center;
        margin: 5px auto;
    }
    .qrcode2-wrapper {
        position: absolute;
        bottom: 30px;
        right: 5px;
    }
    .qrcode2-wrapper p {
        margin:0 auto;
        color: #fff;
        text-align: center;
    }
    .qrcode2-wrapper img {
        width: 120px;
        margin-right: 10px;
     
    }
    .dialog-foot {
        padding-left: 20px;
        padding-top: 10px;
    }
    .dialog-foot p {
        padding: 10px;
    }
</style>

</head>
<body>
<div class="dialog-wrapper">
    <div class="dialog dialog_1 border">
        <div class="dialog-heading1">
       
            <p>尊敬的医生：</p>
            <p style="padding-left:20px;">您已成功完成本次报名</p>
        </div>
 
        <div class="dialog-body1">
            <div class="desc">
                <p>获取开课通知&课程资料</p>
                <p>敬请关注</p>
                <p>400-864-8883</p>
             </div>
            <div class="qrcode2-wrapper">
                <img src="/image/qrcode2.jpg">
                <p>迈徳医学V</p>
            </div>
        </div>
        <div class="dialog-foot">
            <p>痛风课公开课QQ群：497630169</p>
            <p>公开课网址：<a href="/">open.mime.org.cn<a></p>
        </div>
    </div>
</div>
</body>
</html>