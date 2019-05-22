<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>三级分销</title>
    <link rel="shortcut icon" href="{{asset('index/images/favicon.ico')}}" />
    
    <!-- Bootstrap -->
    <link href="{{asset('index/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('index/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('index/css/response.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>

    
  </head>
  <body>
    <div class="maincont">
      @yield('content')