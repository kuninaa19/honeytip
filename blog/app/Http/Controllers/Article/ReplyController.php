<?php
// 2. store() 대댓글 DB저장
// 3. edit () 대댓글 수정하기위한 작성된 댓글 내용가져오기
// 4. update() 대댓글 수정
// 5. destroy() 대댓글 삭제
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
        $name = $request->input('userName');
        $reply = $request->input('reply');
        $parent = $request->input('indexComment');
        $postNum = $request->input('postNum');

        $orderNum = DB::table('reply')->where(['postNum'=>$postNum,'parentComment'=>$parent])->orderBy('order','desc')->first();

        if(empty($orderNum->indexReply)){
            $store = DB::table('reply')->insertGetId(['userName' => $name, 'reply' => $reply,
                'postNum' => $postNum, 'parentComment'=> $parent, 'date'=>NOW(),'order'=>0]);
        } else{
            $store = DB::table('reply')->insertGetId(['userName' => $name, 'reply' => $reply,
                'postNum' => $postNum, 'parentComment'=> $parent, 'date'=>NOW(),'order'=>$orderNum->order+1]);
        }

        $confirm = DB::table('reply')->where('indexReply',$store)->first();

        //DB검색해서 가져온 값이 비었는지 확인
        if(empty($confirm->indexReply)){
            $data = array(
                'key'=>false
            );
        }
        else {
            $data = array(
                'key'=>true,
                'replyIndex'=>$confirm
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    // 대댓글 수정하기위한 작성된 댓글 내용가져오기
    public function edit($id)
    {
        $reply = DB::table('reply')->where('indexReply', $id)->first();

        if(empty($reply->indexReply)){
            $data = array(
                'key'=>false
            );
        }
        else{
            $data = array(
                'key'=>true,
                'comment' => $reply->reply
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    // 대댓글 수정하기 버튼 누르고 DB에 수정된 댓글 저장
    public function update(Request $request, $id)
    {
        $reply = $request->input('reply');

        DB::table('reply')->where('indexReply', $id)->update(['reply'=>$reply]);

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    // 대댓글 삭제
    public function destroy($id)
    {
        $confirm = DB::table('reply')->where('indexReply',$id)->first();

        //DB검색해서 가져온 값이 비었다. 이미 삭제됨
        if(empty($confirm->indexReply)){
            $data = array(
                'key'=>false
            );
            return json_encode($data,JSON_UNESCAPED_UNICODE);
        }

        DB::table('reply')->where('indexReply', $id)->delete();

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}
