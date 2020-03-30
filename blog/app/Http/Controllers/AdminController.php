<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $id = $request->input('id');
        $pw = $request->input('pw');

        $admin = DB::table('admin')->where([['id', $id], ['pw', $pw]])->get();

        //DB검색해서 가져온 값이 비었는지 확인
        if (empty($admin[0]->id)) {
            $data = array(
                'key' => false
            );
        } else {
            $data = array(
                'key' => true,
//                'user'=> array (
//                        'firstName' => 'Brett',
//                        'lastName' => 'McLaughlin',
//                        'email' => 'brett@newInstance.com',
//                    )
            );
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE);
//      return response()->json($data);
//      return response()->json([$value]);
    }
}
