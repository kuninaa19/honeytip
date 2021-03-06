<?php
// get_user_list() 해당 게시글 좋아요 누른 유저 정보 가져오기 [method]
// user_like() 유저 좋아요 리스트에 추가/삭제 [method]
// like_check() 유저가 보고있는 글에 유저 좋아요를 했는지 안했는지 확인
// store() 좋아요 증가

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
//        $this->middleware('auth')->only('store','edit','update','destroy');
//        $this->middleware('auth')->except('index','category_list','viewUp','show');
    }

    // 해당 게시글 좋아요 누른 유저 정보 가져오기
    public function get_user_list($postNum)
    {

        $user = DB::table('post_like')
            ->select('likedPeople')
            ->where('postNum', $postNum)
            ->get();

        return $user;
    }

    //유저 좋아요 리스트에 추가/삭제 [method]
    public function user_like($id, $postNum, $value)
    {
        $likeList = DB::table('users')
            ->select('user_like')
            ->where('uid', $id)
            ->get();

        $userLike = $likeList[0]->user_like;

        // 좋아요 클릭인지 확인
        if ($value === 'possible') {
            // 만약 유저 좋아요가 0개라면
            if (empty($userLike)) {
                $userLike = $postNum;

                DB::table('users')
                    ->where('uid', $id)
                    ->update(['user_like' => $userLike]);
            } else {
                $userLike = $userLike . ',' . $postNum;

                DB::table('users')
                    ->where('uid', $id)
                    ->update(['user_like' => $userLike]);
            }
        } else if ($value === 'cancel') {
            $likeArr = explode(',', $userLike);

            for ($i = 0; $i < count($likeArr); $i++) {
                if ($likeArr[$i] === $userLike) {
                    unset($likeArr[$i]);
                    break;
                }
            }
            $likeStr = implode(',', $likeArr);

            DB::table('users')
                ->where('uid', $id)
                ->update(['user_like' => $likeStr]);
        }
    }

    //유저가 보고있는 글에 유저 좋아요를 했는지 안했는지 확인
    public function like_check($postNum, $id)
    {
        // 게스트 유저면 바로 false;
        if ($id === "guest") {
            return false;
        }

        // 해당 게시글 좋아요 누른 유저 정보 가져오기
        $users = $this->get_user_list($postNum);

        if ($users[0]->likedPeople === null) {
            $data = array('key' => "possible");
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        // JSON Object -> PHP Array(True) 또는 Object(False or 없음) 변환
        $idList = json_decode($users[0]->likedPeople);

        $ck = 0;
        // 유저 정보가 포함되어있는지 확인
        for ($i = 0; $i < count($idList->ID); $i++) {
            if ($id === $idList->ID[$i]->user) {
                $ck = 1;
                break;
            }
        }

        //좋아요 취소
        if ($ck === 1) {
            $data = array('key' => "cancel");
        } //좋아요 누를 수 있음
        else {
            $data = array('key' => "possible");
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE);

    }

//글 좋아요 클릭(+1) 클릭된것을 한번더 누르면 -1
    public function store(Request $request)
    {
        $num = $request->input('postNum');
        $id = $request->input('id');
        $value = $request->input('value'); //좋아요인지 좋아요 취소인지

        $content = DB::table('posts')
            ->select('likeIt')
            ->where('indexPosts', $num)
            ->get();

        $this->user_like($id, $num, $value);

        // 좋아요 클릭
        if ($value === "possible") {
            //좋아요 1증가
            DB::table('posts')
                ->where('indexPosts', $num)
                ->update(['likeIt' => $content[0]->likeIt+1]);

            //해당 게시글 좋아요 첫 유저
            if ($content[0]->likeIt === 0) {
                //유저아이디저장하기위한 문자열
                $userName = '{"ID":[{"user":"' . $id . '"}]}';

                // 유저아이디 배열로 변환 JSON Object -> PHP Array(True) 또는 Object(False or 없음) 변환
                $userArr = json_decode($userName, true);

                 DB::table('post_like')
                    ->where('postNum', $num)
                    ->update(['likedPeople' => $userArr]);
            } else {
                // 해당 게시글 좋아요 누른 유저 정보 가져오기
                $users = $this->get_user_list($num);

                //저장에 대한 세부내용 로그 (PHP용 Console.log)
//                var_dump(json_decode($users[0]->likedPeople,true));

                //유저아이디저장하기위한 문자열
                $userName = '{"user":"' . $id . '"}';

                // 유저아이디 배열로 변환 JSON Object -> PHP Array(True) 또는 Object(False or 없음) 변환
                $userArr = json_decode($userName, true);

                //기존좋아요 누른 유저아이디 배열리스트
                $idList = json_decode($users[0]->likedPeople, true);

                //기존유저배열에 추가
                array_push($idList["ID"], $userArr);

                DB::table('post_like')
                    ->where('postNum', $num)
                    ->update(['likedPeople' => $idList]);
            }
        } //좋아요 취소
        else if ($value === "cancel"){
            //좋아요 1감소
            DB::table('posts')
                ->where('indexPosts', $num)
                ->update(['likeIt' => $content[0]->likeIt - 1]);

            // 해당 게시글 좋아요 누른 유저 정보 가져오기
            $users = $this->get_user_list($num);

            // JSON Object -> PHP Array(True) 또는 Object(False or 없음) 변환
            $idList = json_decode($users[0]->likedPeople, true);

            // 유저 정보가 포함되어있는지 확인
            for ($i = 0; $i < count($idList["ID"]); $i++) {
                if ($id === $idList["ID"][$i]["user"]) {
                    array_splice($idList["ID"], $i, 1);
                    break;
                }
            }

            // 유저아이디 배열로 변환 JSON Object -> PHP Array(True) 또는 Object(False or 없음) 변환
            $userArr = json_encode($idList);

            DB::table('post_like')
                ->where('postNum', $num)
                ->update(['likedPeople' => $userArr]);
        }

        // 좋아요개수 반납
        $like = DB::table('posts')
            ->select('likeIt')
            ->where('indexPosts', $num)
            ->first();

        $data = array(
            'key' => true,
            'likeIt' => $like->likeIt
        );

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
