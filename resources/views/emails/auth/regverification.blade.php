<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <a href='/'>{!! HTML::image('http://laravel.com/assets/img/laravel-logo.png','logo') !!}</a>
            <h2>Thanks for registering!</h2>

        <div>
            To complete the registration process you need to verify your email address simply by clicking on the link below<br>
            {!! URL::to('register/verify/' . $confirmation_code) !!}.<br/>
        </div>
        <div style='font-size:14px;margin-top:20px;color:#F9604C;'>
            Laravel 5 app scaffolding!
        </div>
    </body>
</html>