<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/icons/icon1.png')}}">

    <title>Product Ordering Communication System</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">

    <link href="{{ asset('assets/css/datatable.css') }}" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
        .navbar-default {
            background-color: #243665;
            border-color: #8bd8bd;
        }
        .btn{
            width: 100%;
        }
    </style>
    @yield('customcss')
</head>
<body id="app-layout">
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{ url('/') }}" style="color:#8bd8bd; height: 65px; padding: 7px 15px;">
        <span style="display: inline; padding-right: 15px;"><i class="fas fa-users" style="font-size: 50px"></i></span>
        Product Ordering Communication System
    </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li>
            <a style="color:#8bd8bd;height: 0px; padding: 34px 15px; font-size: 16px;" href="{{ asset('/manual/user_manual.pdf') }}" download>User Manual</a>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

    @yield('content')

    <!-- JavaScripts -->
    <script src="{{ asset('assets/js/jquery.3.2.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/jquery-datatables.js') }}"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    @yield('customjs')
</body>
</html>
