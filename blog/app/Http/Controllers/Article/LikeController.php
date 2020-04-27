<?php
// get_user_list() 해당 게시글 좋아요 누른 유저 정보 가져오기 [method]
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
    public function  get_user_list($postNum){

        $user = DB::table('post_like')
            ->select('likedPeople')
            ->where('postNum', $postNum)
            ->get();

        return $user;
    }
    //유저가 보고있는 글에 유저 좋아요를 했는지 안했는지 확인
    public function like_check($postNum,$id){

        $users = $this->get_user_list($postNum);

        // JSON Object -> PHP Array(True) 또는 Object(False or 없음) 변환
        $idList =  json_decode($users[0]->likedPeople);

        $ck = 0;
        // 유저 정보가 포함되어있는지 확인
        for($i=0;$i< count($idList->ID);$i++){
            if($id===$idList->ID[$i]->user){
                $ck=1;
                break;
            }
        }

        //좋아요 누를 수 있음
        if($ck===1){
            return true;
        }
        //좋아요 누를 수 없음
        else{
            return false;
        }
    }

//글 좋아요 클릭(+1) 클릭된것을 한번더 누르면 -1
    public function store(Request $request)
    {
        $num = $request->input('postNum');
        $id = $request->input('id');
        $value = $request->input('value'); //좋아요인지 좋아요 취소인지

        $content = DB::table('posts')
            ->select('likeIt')
            ->where('indexPosts',$num)
            ->lockForUpdate()
            ->get();

        // 좋아요 클릭
        if($value===true){
            //좋아요 1증가
            DB::table('posts')
                ->where('indexPosts', $num)
                ->update(['likeIt' => $content[0]->likeIt+1]);

            $users = $this->get_user_list($num);

            // JSON Object -> PHP Array(True) 또는 Object(False or 없음) 변환
            $idList =  json_decode($users[0]->likedPeople);

            $ck = 0;
            for($i=0;$i< count($idList->ID);$i++){
                if($id===$idList->ID[$i]->user){
                    $ck=1;
                    break;
                }
            }

            //좋아요 누름
            if($ck===1){
                return "있음";
            }
            else{
                return "없음";
            }

//            //좋아요 누른 유저로 등록
//            DB::table('post_like')
//                ->where('postNum', $num)
//                ->update(['likedPeople->ID' =>'{"ID":[{"user" : "'.$id.'"}]}']);
//
//
//            $value = '[{user : '.$id.'}]';
////        let user_id =  \'{"ID" : [{"user" : "\'+user+\'"}]}\';
//
//            // 채팅방에 참가중인 아이디인지 확인
//            for(var i=0;i<objStr.ID.length;i++){
//                if(objStr.ID[i].user===userID){
//                    ck=1;
//                    break;
//                }
//            }
//        DB::table('post_like')
//            ->where('postNum', $num)
//            ->update(['likedPeople->ID' =>'{"ID":[{"user" : "'.$id.'"}]}']);
        }
        //좋아요 취소
        else{

        }

        // 좋아요개수 반납
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
}
