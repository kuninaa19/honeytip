<?php
// comments_list() 댓글 리스트 목록 (페이징)
// index()  댓글 리스트 전체목록(관리자페이지)
// store() 댓글 DB저장
// edit () 댓글 수정하기위한 작성된 댓글 내용가져오기
// update() 댓글 수정
// destroy() 댓글 삭제

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
//        $this->middleware('auth')->only('store','edit','update','destroy');
//        $this->middleware('auth')->except('comments_list');
    }

    // 댓글 리스트 목록 (페이징)
    // 파라미터 글 번호 , 댓글 페이징 번호
    public function comments_list($postNum,$page)
    {
        $contentCount = DB::table('comments')
            ->where(['postNum'=> $postNum,'class'=> 0])->count();
//            ->orderBy('indexComments', 'asc')->offset(($page-1)*6)->limit(6)->count();


        //페이지네이션 페이지마다 최소요구개수를 충족하는지 판단
        if ($contentCount<(($page-1)*6+1)) {
            $data = array(
                'key' => false
            );
            return json_encode($data,JSON_UNESCAPED_UNICODE);
        }

        //페이지네이션 내부 댓글 인덱스번호(배열형태)
        $indexNums = DB::table('comments')
            ->where(['postNum'=> $postNum,'class'=> 0])
            ->offset(($page-1)*6)->limit(6)
            ->pluck('indexComments');

//        return json_encode($indexNums,JSON_UNESCAPED_UNICODE);

        // 페이지네이션번호마다 포함되는 대댓글 개수파악
        $replyCount = DB::table('comments')
            ->where(['postNum'=> $postNum,'class'=> 1])
            ->whereIn('groupNum',$indexNums)
            ->pluck('indexComments');
//            ->count();

        //배열 합치기
        $array =Arr::collapse([$indexNums, $replyCount]);

        $content = DB::table('comments')->where('postNum', $postNum)
            ->whereIn('groupNum',$array)
            ->orderBy('groupNum','asc')
            ->orderBy('indexComments','asc')
            ->orderBy('order','asc')
            ->get();
//            ->offset(($page-1)*6)->limit(6)->get();

        $data = array(
                'key' => true,
                'contents' => $content
            );

        return json_encode($data,JSON_UNESCAPED_UNICODE);
//      return response()->json($data);
    }

    // 댓글 리스트 전체목록(관리자페이지)
    public function index()
    {
        $content = DB::table('comments')
            ->select('userName','category','date','indexComments','comment')
            ->orderBy('indexComments', 'desc')
            ->get();

        if (empty($content[0])) {
            $data = array(
                'key' => false
            );
        } else {
            $data = array(
                'key' => true,
                'contents' => $content,
            );
        }

        return json_encode($data,JSON_UNESCAPED_UNICODE);
//      return response()->json($data);
    }

    // 댓글 DB저장하기
    public function store(Request $request)
    {
        $name = $request->input('userName');
        $comment = $request->input('comment');
        $category = $request->input('category');
        $postNum = $request->input('postNum');
        $uid = $request->input('uid');

        //uid가 값이 없다면 거절메세지
        if(empty($uid)){
            $data = array(
                'key'=>false
            );
            return json_encode($data,JSON_UNESCAPED_UNICODE);

        }

        $store = DB::table('comments')->insertGetId(['userName' => $name, 'comment' => $comment,
            'postNum' => $postNum, 'uid'=>$uid, 'category'=> $category, 'date'=>NOW()]);

        DB::table('comments')->where('indexComments', $store)->update(['groupNum'=>$store]);

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
