<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>403</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
    <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm-extend.min.css">
    <link rel="stylesheet" type="text/css" href=" {{ url('/css/main.css') }}" />
    <style type="text/css">
        .main-404 {
            text-align: center;
        }

        .main-404 img {
            width: 11rem;
            margin-top: 4.2rem;
        }
        .buttom_home{
            text-align: center;
            margin-top: 3rem;
        }
        .buttom_home a:nth-child(1){
            margin: 10px;
            padding: 10px;
            background: #0894ec;
            color: #ffffff;
            width: 7rem;
            display: inline-block;
            border-radius: 5px;
            transition: all .5s ease;
            border:1px solid #efeff4;
        }
        .buttom_home a:nth-child(1):hover{
            background: #ffffff;
            color: #0894ec;
            border:1px solid #0894ec;
        }
        .buttom_home a:nth-child(2){
            margin: 10px;
            padding: 10px;
            background: #ffffff;
            color: #0894ec;
            width: 7rem;
            display: inline-block;
            border-radius: 5px;
            transition: all .5s ease;
            border:1px solid #0894ec;
        }
        .buttom_home a:nth-child(2):hover{
            background: #0894ec;
            color: #ffffff;
            border:1px solid #efeff4;
        }
    </style>
</head>

<body>
<div class="page-group">
    <div class="page page-current">
        <!-- 你的html代码 -->
        <div class="content_403">
            <div class="main-404">
                <img src=" {{ url('images/404new1.png') }}"/>
                <p>对不起，你没有权限操作这个功能...</p>
            </div>
        </div>
        <div class="buttom_home">
            <a href="javascript:window.history.go(-1)" class="btn btn-success btn-block">返回上一页</a>
            <a href="{{ url('/admin') }}" class="btn btn-success btn-block">首页</a>
        </div>

    </div>
</div>
</body>

</html>