<?php
// 1. show() 회원정보 메인 페이지
// 2. destroy() 회원탈퇴

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
//        $this->middleware('auth')->only('store','edit','update','destroy');
//        $this->middleware('auth')->except('comments_list');
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    // 회원정보 메인페이지
    public function show($id)
    {
        $user = DB::table('users')->where( 'uid', $id)
            ->select('name', 'avatar','badge')
            ->first();

//        return json_encode($user,JSON_UNESCAPED_UNICODE);

        //DB검색해서 가져온 값이 존재하는지 확인
        if(isset($user->name)){
            $data = array(
                'key'=>true,
                'userInfo'=> array (
                  $user
                )
            );
        }
        else{
            $data = array(
                'key'=>false
        );
        }

        return json_encode($data,JSON_UNESCAPED_UNICODE);
//     return response()->json($data);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    //회원탈퇴
    public function destroy($id)
    {
        $ckUser = DB::table('users')->where('uid',$id)
            ->select('uid')
            ->first();

        //DB검색해서 가져온 값이 비었다. 이미 삭제됨
        if(empty($ckUser)){
            $data = array(
                'key'=>false
            );
            return json_encode($data,JSON_UNESCAPED_UNICODE);
        }

        DB::table('users')->where('uid', $id)->delete();

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}
