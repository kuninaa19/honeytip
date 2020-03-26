<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
HI {{$info}}

@foreach ($info as $user)
{{ $user->email }}
@endforeach

<form action="info" method="POST">
    {{ csrf_field() }}
    <input type="text" name="email" placeholder="이메일">
    <input type="text" name="id" placeholder="이름">
    <input type="text" name="sns_type" placeholder="sns">
    <input type="submit">
</form>
</body>
</html>
