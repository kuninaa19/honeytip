<?php
// 1. store_secret() 관리자 아이디 생성
// 2. login () 관리자 로그인인증

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
    // 관리자 아이디 생성
    public function store_secret(Request $request)
    {
        $id = $request->input('id');
        $pw = $request->input('pw');
        $nickName = $request->input('nickName');

        $encrypted = Crypt::encryptString($pw);

        DB::table('admin')->insert(['id' => $id, 'pw'=> $encrypted,'nickName'=>$nickName]);
    }

    // 관리자 로그인인증
    public function login(Request $request)
    {
        $id = $request->input('id');
        $pw = $request->input('pw');

        $admin = DB::table('admin')->where('id', $id)->get();

        //DB검색해서 아이디 있는지 확인
        if (empty($admin[0]->id)) {
            $data = array(
                'key' => false
            );
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        else{
            $decrypted = Crypt::decryptString($admin[0]->pw);

            if($pw === $decrypted){
                $data = array(
                    'key' => true
                );
                return json_encode($data, JSON_UNESCAPED_UNICODE);
            }
            else{
                $data = array(
                    'key' => false
                );
                return json_encode($data, JSON_UNESCAPED_UNICODE);
            }
        }
//      return response()->json($data);
    }
}
