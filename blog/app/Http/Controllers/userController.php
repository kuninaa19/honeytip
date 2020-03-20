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

    public function aa(){
        return request()->all();
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
