<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <a href='/'>{!!HTML::image('http://laravel.com/assets/img/laravel-logo.png','logo') !!}</a>
        <h2>
            @yield('title')
        </h2>
        <div>
            @yield('body')
        </div>
        <div style='font-size:14px;margin-top:20px;color:#F9604C;'>
            Laravel 5 app scaffolding!
        </div>
    </body>
</html>
