<?php
// store() 좋아요 증가

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    //좋아요 증가
    public function store(Request $request)
    {

        $content = DB::table('posts')
            ->select('likeIt')
            ->where('indexPosts',$num)
            ->lockForUpdate()
            ->get();

        DB::table('posts')
            ->where('indexPosts', $num)
            ->update(['likeIt' => $content[0]->likeIt+1]);

        $like = DB::table('posts')
            ->select('likeIt')
            ->where('indexPosts',$num)
            ->first();

        $data = array(
            'key'=>true,
            'likeIt'=>$like->likeIt
        );

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
