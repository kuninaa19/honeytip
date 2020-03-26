<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
</head>
<body>
<script>
    function get_csrf(callback){
        $.ajax({
            type: 'get'
            ,url: 'https://honeytip.p-e.kr/csrf_token'
            ,data: ''
            ,xhrFields: {
                withCredentials: false
            }
            ,success: function(data){
                console.log(data)
                callback(data) // 받아온 csrf_token을 반환해주는 부분
            }
            ,error: function(xhr, status, msg){
                console.log(xhr)
            }
        });
    }

    function test_ajax1(csrf_token){

        var id = $('#id').val();
        console.log(id);
        var pw = $('#pw').val();

        var tmp1 = {
            '_token': csrf_token, //이부분에서 '_token'이라는 key로 csrf_token값을 전달해 주어야 한다
            'id':id,
            'pw':pw
        }

        console.log(tmp1);
        // var myJSON = JSON.stringify(tmp1);
        //
        // console.log(myJSON);

        $.ajax({
            type: 'post'
            ,url: 'https://honeytip.p-e.kr/login/auth'
            ,data: tmp1
            // ,dataType:"json"
            ,xhrFields: {
                withCredentials: false
            }
            ,success: function(data){
                //json 해체
                data = JSON.parse(data);
                //위의 코드 없으면 문자형
                console.log(data);
                $('#end').val(data);


            }
            ,error: function(xhr, status, msg){
                console.log(xhr);
            }
        });
    }

    function get_token(){
        get_csrf(function(csrf_token){
            test_ajax1(csrf_token)
        })
    }
</script>

    <input type="text" name="id"  id ="id" placeholder="아이디">
    <input type="text" name="pw" id="pw" placeholder="비번">
    <input type="button" onclick="get_token();">

<input type="text" id="end">
</body>
</html>
