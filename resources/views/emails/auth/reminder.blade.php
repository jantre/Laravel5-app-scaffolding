<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<a href='/'>{!!HTML::image('http://www.tillog.com/files/tilloglogo.png','logo') !!}</a>

		<h2>Password Reset</h2>

		<div>
			To reset your password, complete this form: {!! URL::to('password/reset', array($token)) !!}.<br/>
			This link will expire in {!! Config::get('auth.reminder.expire', 60) !!} minutes.
		</div>
        <div style='font-size:14px;margin-top:20px;color:#FF8615;'>
            ...Never stop learning.
        </div>
	</body>
</html>
