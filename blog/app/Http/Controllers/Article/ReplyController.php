<?php
// 1. store() 대댓글 DB저장
// 2. edit () 대댓글 수정하기위한 작성된 댓글 내용가져오기
// 3. update() 대댓글 수정
// 4. destroy() 대댓글 삭제
namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
//        $this->middleware('auth')->only('store','edit','update','destroy');
//        $this->middleware('auth')->except('comments_list');
    }

    //대댓글 DB저장하기
    public function store(Request $request)
    {
        $groupId = $request->input('indexComments');
        $name = $request->input('userName');
        $comment = $request->input('comment');
        $postNum = $request->input('postNum');
        $uid = $request->input('uid');

        //uid가 값이 없다면 거절메세지
        if($uid===null){
            $data = array(
                'key'=>false
            );
            return json_encode($data,JSON_UNESCAPED_UNICODE);

        }

        $orderNum = DB::table('comments')->where(['postNum'=>$postNum,'groupNum'=>$groupId])->orderBy('order','desc')->first();

        $store = DB::table('comments')->insertGetId(['userName' => $name, 'comment' => $comment,
                'postNum' => $postNum, 'uid'=>$uid, 'groupNum'=> $groupId, 'date'=>NOW(),'class'=> 1,'order'=>$orderNum->order+1]);

        $confirm = DB::table('comments')->where('indexComments',$store)->first();

        //DB검색해서 가져온 값이 비었는지 확인
        if(empty($confirm->indexComments)){
            $data = array(
                'key'=>false
            );
        }
        else {
            $data = array(
                'key'=>true,
                'commentsIndex'=>$store
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    // 대댓글 수정하기위한 작성된 댓글 내용가져오기
    public function edit($id)
    {
        $comment = DB::table('comments')->where('indexComments', $id)->first();

        if(empty($comment->indexComments)){
            $data = array(
                'key'=>false
            );
        }
        else{
            $data = array(
                'key'=>true,
                'comment' => $comment->comment
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    // 대댓글 수정하기 버튼 누르고 DB에 수정된 댓글 저장
    public function update(Request $request, $id)
    {
        $comment= $request->input('comment');

        DB::table('comments')->where('indexComments', $id)->update(['comment'=>$comment]);

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    // 대댓글 삭제
    public function destroy($id)
    {
        $confirm = DB::table('comments')->where('indexComments',$id)->first();

        //DB검색해서 가져온 값이 비었다. 이미 삭제됨
        if(empty($confirm->indexComments)){
            $data = array(
                'key'=>false
            );
            return json_encode($data,JSON_UNESCAPED_UNICODE);
        }

        DB::table('comments')->where('indexComments', $id)->delete();

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}
