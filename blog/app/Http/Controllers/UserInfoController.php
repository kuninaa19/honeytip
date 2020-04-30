<?php
// comments_list() 유저 댓글 리스트(페이징)
// like_list() 유저 좋아요 리스트(페이징)
// show() 회원정보 메인 페이지
// destroy() 회원탈퇴

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

    //유저 댓글 리스트(페이징)
    public function comments_list($id, $page)
    {
        $content = DB::table('comments')
            ->select('userName', 'category', 'date', 'indexComments', 'comment', 'postNum')
            ->orderBy('indexComments', 'desc')
            ->where(['uid' => $id])
            ->offset(($page - 1) * 6)->limit(6)
            ->get();

        $count = DB::table('comments')->where(['uid' => $id])->count();

        // 하단 페이지네이션
        $pageCount = ceil(($count / 6));

        //페이지네이션 페이지마다 최소요구개수를 충족하는지 판단
        if (count($content) < 1) {
            $data = array(
                'key' => false,
                'pageCount' => $pageCount
            );
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $data = array(
            'key' => true,
            'contents' => $content,
            'pageCount' => $pageCount
        );

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    //유저 좋아요 리스트(페이징)
    public function like_list($id, $page)
    {
        $likeList = DB::table('users')
            ->select('user_like')
            ->where('uid', $id)
            ->get();

        $userLike = $likeList[0]->user_like;

        //좋아요를 누른적이 있다면
        if (isset($userLike)) {
            $likeArr = explode(',', $userLike);

            // 하단 페이지네이션
            $pageCount = ceil((count($likeArr) / 6));

            //페이지네이션 페이지마다 최소요구개수를 충족하는지 판단
            if (count($likeArr)<(($page-1)*6+1)) {
                $data = array(
                    'key' => false,
                    'pageCount' => $pageCount
                );
                return json_encode($data,JSON_UNESCAPED_UNICODE);
            }

            $output = array_slice($likeArr,(($page-1)*6),6);

            $content = DB::table('posts')
                ->select('indexPosts','subTitle','title','category')
                ->whereIn('indexPosts',$output)
                ->get();

            $data = array(
                'key' => true,
                'contents' => $content,
                'pageCount' => $pageCount
            );

            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $data = array(
            'key' => false,
            'pageCount' => 0
        );

        return json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    // 회원정보 메인페이지
    public function show($id)
    {
        $user = DB::table('users')->where('uid', $id)
            ->select('name', 'avatar', 'badge')
            ->first();

        //DB검색해서 가져온 값이 존재하는지 확인
        if (isset($user->name)) {
            $data = array(
                'key' => true,
                'userInfo' => array(
                    $user
                )
            );
        } else {
            $data = array(
                'key' => false
            );
        }

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    //회원탈퇴
    public function destroy($id)
    {
        $ckUser = DB::table('users')->where('uid', $id)
            ->select('uid')
            ->first();

        //DB검색해서 가져온 값이 비었다. 이미 삭제됨
        if (empty($ckUser)) {
            $data = array(
                'key' => false
            );
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        DB::table('users')->where('uid', $id)->delete();

        $data = array(
            'key' => true
        );
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
