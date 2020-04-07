<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Social Login In Laravel 5.6</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Styles -->
    <style>
        body {
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .social-login {
            display: flex;
            flex-direction: column;
            background-color: white;
            justify-content: center;
            width: 400px;
            height: 220px;
            max-width: 50vw;
            padding: 15px 35px;
            border-radius: 5px;
            box-shadow: 0 10px 40px 0 rgba(0, 0, 0, 0.1);
        }
        .social-login h2 {
            margin: 20px 0 20px;
            padding: 0;
            text-transform: uppercase;
            color: #000;
            position: relative;
            text-align: center;
        }
        .social-button--facebook {
            list-style: none;
            background-color: transparent;
            border-width: 2px;
            border-style: solid;
            border-color: #3A5998;
            color: #3A5998;
            cursor: pointer;
            font-size: 14px;
            padding: 15px;
            display: flex;
            border-radius: 5px;
            align-items: center;
            text-decoration: none;
            text-transform: uppercase;
            transition: background-color 250ms ease-out, color 250ms ease-out;
            font-weight: 700;
            position: relative;
            margin: 10px 0;
            z-index: 0;
            will-change: background-color, color;
            user-select: none;
            -webkit-font-smoothing: antialiased;
        }
        .social-button--facebook:focus, .social-button--facebook:hover {
            background-color: #bac8e4;
        }
        .social-icon--facebook {
            color: white;
            font-size: 18px;
        }
        .social-icon--facebook:after {
            content: "";
            background-color: #3A5998;
            position: absolute;
            width: 50px;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .social-button--google {
            list-style: none;
            background-color: transparent;
            border-width: 2px;
            border-style: solid;
            border-color: #DB4437;
            color: #DB4437;
            cursor: pointer;
            font-size: 14px;
            padding: 15px;
            display: flex;
            border-radius: 5px;
            align-items: center;
            text-decoration: none;
            text-transform: uppercase;
            transition: background-color 250ms ease-out, color 250ms ease-out;
            font-weight: 700;
            position: relative;
            margin: 10px 0;
            z-index: 0;
            will-change: background-color, color;
            user-select: none;
            -webkit-font-smoothing: antialiased;
        }
        .social-button--google:focus, .social-button--google:hover {
            background-color: #fae6e4;
        }
        .social-icon--google {
            color: white;
            font-size: 18px;
        }
        .social-icon--google:after {
            content: "";
            background-color: #DB4437;
            position: absolute;
            width: 50px;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .social-text {
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="social-login">
    <h2>Social Login in Laravel 5.6</h2>
    <a href="{{ url('/auth/social/google') }}" class="social-button--google">
        <i class="social-icon--google fa fa-google"></i>
        <span class="social-text">
Sign in with google
</span>
    </a>
    <a href="{{ url('/auth/social/facebook') }}" class="social-button--facebook">
        <i class="social-icon--facebook fa fa-facebook"></i>
        <span class="social-text">
Sign in with facebook
</span>
    </a>
</div>
</body>
</html>
