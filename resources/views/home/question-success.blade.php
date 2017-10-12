<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>问卷提交成功</title>
<link rel="stylesheet" type="text/css" href="/css/page.css">

<script>
function closeWebPage(){  
 if (navigator.userAgent.indexOf("MSIE") > 0) {//close IE  
  if (navigator.userAgent.indexOf("MSIE 6.0") > 0) {  
   window.opener = null;  
   window.close();  
  } else {  
   window.open('', '_top');  
   window.top.close();  
  }  
 }  
 else if (navigator.userAgent.indexOf("Firefox") > 0) {//close firefox  
  window.location.href = 'about:blank ';  
 } else {//close chrome;It is effective when it is only one.  
  window.opener = null;  
  window.open('', '_self');  
  window.close();  
 }  
} 



</script>
</head>

<body>

<div class="dialog dialog_2">
<div class="dialog-heading">
    <span class="with-icon-right">问卷提交成功！</span>
</div>
<div class="dialog-body">
    <a class="close-button" href="/">开始学习</a>
</div>
</div>
</div>
</body>


</body>
</html>