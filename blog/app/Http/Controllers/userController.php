<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class userController extends Controller
{
    public function index()
    {
        $users = DB::table('user')->get();

        return view('index', ['info' => $users]);
    }

    public function example(){
        return view('ajax_example');

    }

    public function login(Request $request){

//                return $request->all();
//        return json_encode($request);
//        return $request->all();
//
//        $db = json_decode($request);
//
//        $id = $db->input('id');
//        $pw = $db->input('pw');

        $id = $request->input('id');
        $pw = $request->input('pw');

        //값 존재 유뮤 확인
        $key = false;

//        테이블 값 전체 가져오기
//        $users = DB::table('user')->get();

//        테이블 where조건에서 가장 상단의 값만 가져옴
//        $users = DB::table('user')->where('sns_type', $email)->first();

//        $admin = DB::table('admin')->whereColumn('id', $id,'pw',$pw)->get();
        $admin = DB::table('admin')->where('id', $id)->get();

        //DB검색해서 가져온 값이 있는지 확인
        // 화살표옆 email은 칼럼이름(key)
        if(empty($admin[0]->id)){
            $data = array(
                'key'=>$key,
            );
//            json형태전송
            $db = json_encode($data);
            return $db;
            //문자형태 전송
//            return $data;
        }
        else{
            $key = true;

            $data = array(
                'key'=>$key,
                'user'=>$admin,
            );

            $db = json_encode($data);
            return $db;

//            return $data;
        }
    }

    public function aa(Request $request){
//        return $request->all();

        $type = $request->input('sns_type');

        //값 존재 유뮤 확인
        $key = false;

//        테이블 값 전체 가져오기
//        $users = DB::table('user')->get();

//        테이블 where조건에서 가장 상단의 값만 가져옴
//        $users = DB::table('user')->where('sns_type', $email)->first();

        $users = DB::table('user')->where('sns_type', $type)->get();

        //DB검색해서 가져온 값이 있는지 확인
        // 화살표옆 email은 칼럼이름(key)
        if(empty($users[0]->email)){
            $data = array(
                'key'=>$key,
            );
            return $data;
        }
        else{
            $key = true;

            $data = array(
                'key'=>$key,
                'user'=>$users,
            );

            return $data;
        }
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $type = $request->sns_type;

//        $data = json_decode($request->getContent(), true);  // $request->getContent()는 request body
//        $name = $data['name']; //json_decode에 의해 json data가 array 형식으로 $data에 담긴다.
//        $email = $data['email'];
//        $type = $data['type'];

        DB::table('user')->insert(['email' => $email, 'name' => $name, 'sns_type' => $type]
        );

        return response()->json(['response_name' => $name, 'response_email' => $email,
        'sns_type'=>$type]);
//        return view('index', ['info' => $info]);
    }

    public function about()
    {
        return view('iffor')->with([
            'greeting' => 'Good morning ^^/',
            'name' => 'Appkr',
            'items' => ['Apple', 'Banana']
        ]);
    }

    public function  name($info)
    {

        return view('iffor')->with([
            'greeting' => 'Good morning ^^/',
            'name' => 'Appkr',
            'items' => [$info, 'Banana']
        ]);

    }
}
