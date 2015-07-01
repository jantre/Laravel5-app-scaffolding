<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Laravel Application</title>
      <!-- ion icons -->
      <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
      <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

      {!! HTML::style('css/main.css') !!}
  </head>
  <body>
    @if(Auth::check())
      @include('navbar.authenticated')
    @else
      @include('navbar.anonymous')
    @endif
    <div class='container'>
      @include('notifications')
      @yield('content')
    </div>
    <div class="container">
        @include('footer')
    </div>
  </body>
</html>
