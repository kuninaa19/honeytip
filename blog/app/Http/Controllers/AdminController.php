<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function login(Request $request){
        $id = $request->input('id');
        $pw = $request->input('pw');

//      테이블 where조건에서 가장 상단의 값만 가져옴
//      $admin = DB::table('admin')->where('id', $id)->get();
//      $users = DB::table('user')->where('sns_type', $email)->first();
        $admin = DB::table('admin')->where([['id', $id], ['pw', $pw]])->get();

        //값 존재 유뮤 확인
        $key = false;

        //DB검색해서 가져온 값이 있는지 확인
        // 화살표옆 email은 칼럼이름(key)
        if(empty($admin[0]->id)){
            $data = array(
                'key'=>$key
            );

            //객체를 문자열형태로 만들기
            $value = json_encode($data);
            return $value;

//          return response()->json($data);
        }
        else{
            $key = true;

            $data = array(
                'key'=>$key,
                'user'=>$admin,
            );
//
            $value = json_encode($data,JSON_UNESCAPED_UNICODE);
            return $value;

//            return response()->json($data);
        }

        //        $my_array = array (
//            'programmers' =>
//                array (
//                    array (
//                        'firstName' => 'Brett',
//                        'lastName' => 'McLaughlin',
//                        'email' => 'brett@newInstance.com',
//                    ),
//                    array (
//                        'firstName' => 'Jason',
//                        'lastName' => 'Hunter',
//                        'email' => 'jason@servlets.com',
//                    ),
//                ),
//        );
    }
}
