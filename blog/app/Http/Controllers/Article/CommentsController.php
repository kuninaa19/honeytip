<?php
// 1. comments_list() 댓글 리스트 목록 (페이징)
// 2. store() 댓글 DB저장
// 3. edit () 댓글 수정하기위한 작성된 댓글 내용가져오기
// 4. update() 댓글 수정
// 5. destroy() 댓글 삭제

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
        $this->middleware('auth')->only('store','edit','update','destroy');
    }

    // 댓글 리스트 목록 (페이징)
    // 파라미터 글 번호 , 댓글 페이징 번호
    public function comments_list($postNum,$page)
    {
        $content = DB::table('comments')->where('postNum', $postNum)
            ->orderBy('indexComments', 'desc')->offset(($page-1)*6)->limit(6)->get();

        ;
//        return $content[0];
//        return json_encode($content[0]->date);
        if (empty($content[0])) {
            $data = array(
                'key' => false
            );
        } else {
            $data = array(
                'key' => true,
                'contents' => $content
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
//      return response()->json($data);
    }

    //댓글 DB저장하기
    public function store(Request $request)
    {
        $name = $request->input('userName');
        $comment = $request->input('comment');
        $category = $request->input('category');
        $postNum = $request->input('postNum');

        $store = DB::table('comments')->insertGetId(['userName' => $name, 'comment' => $comment,
            'postNum' => $postNum, 'category'=> $category, 'date'=>NOW()]);

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
                'commentIndex'=>$store
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    // 댓글 수정하기위한 작성된 댓글 내용가져오기
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

    // 댓글 수정하기 버튼 누르고 DB에 수정된 댓글 저장
    public function update(Request $request, $id)
    {
        $comment = $request->input('comment');

        DB::table('comments')->where('indexComments', $id)->update(['comment'=>$comment]);

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    // 댓글 삭제
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
