<?php
//comments_count() 게시글 댓글 개수(삭제 수정 삭제시 처리)
// post_list() 카테고리별 글 리스트 전체목록(관리자페이지)
// popularity_ranking() 카테고리별 인기순위 정보
// category_list() 카테고리별 글 리스트 목록
// image_store() 글생성 전 이미지 저장
// recommend_list() 글 상세페이지 하단 글 추천 목록( 6개 랜덤)
// index() 메인페이지 최신 글 6개 보내주기
// store() 글 생성
// show () 글 상세페이지
//  edit () 글 수정하기위한 작성된 글 내용가져오기
// update() 글 수정
//  destroy() 글 삭제

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
//        $this->middleware('auth')->only('store','edit','update','destroy');
//        $this->middleware('auth')->except('index','category_list','viewUp','show');
    }

    public function comments_count($postNum){
        $count= DB::table('comments')->where(['postNum'=>$postNum,'class'=>0])->count();

        $data = array(
            'commentsCount'=>$count
        );

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    // 카테고리별 글 리스트 전체목록(관리자페이지)
    public  function  post_list($category){
        $content = DB::table('posts')->where('category', $category)
            ->orderBy('indexPosts', 'desc')->get();

        if (empty($content[0])) {
            $data = array(
                'key' => false
            );
        } else {

            $postCount = DB::table('posts')->count();

            $data = array(
                'key' => true,
                'contents' => $content,
                'postCount'=>$postCount
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
//      return response()->json($data);
    }

    //카테고리별 인기순위 정보 [category_list 연관]
    public function popularity_ranking($category){
        $content = DB::table('posts')
            ->select('indexPosts','title','likeIt','subTitle','category')
            ->where('category', $category)
            ->orderBy('likeIt', 'desc')
            ->orderBy('indexPosts', 'desc')
            ->limit(6)
            ->get();

        return $content;
    }

    //글 리스트 목록 (페이징)
    public function category_list($category,$num){
        $content = DB::table('posts')
            ->leftJoin('comments', 'posts.indexPosts', '=', 'comments.postNum')
            ->select('posts.indexPosts','posts.title','posts.likeIt','posts.subTitle','posts.category','posts.date','posts.likeIt','posts.image',DB::raw('count(comments.postNum) as commentsCount'))
            ->groupBy('posts.indexPosts')
            ->where('posts.category', $category)
            ->orderBy('indexPosts', 'desc')->offset(($num-1)*6)->limit(6)->get();

        $rankingContent = $this->popularity_ranking($category);

        if (empty($content[0])) {
            $data = array(
                'key' => false
            );
        } else {
            $data = array(
                'key' => true,
                'contents' => $content,
                'rankingContents'=>$rankingContent
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
//      return response()->json($data);
    }

    //이미지 저장후 주소 전달
    public function image_store(Request $request){
        $image_path = $request->file('fileToUpload')->store('images','public');

        return $image_path;
    }

    //글 상세페이지 하단 글 추천 목록( 6개 랜덤)
    public function recommend_list($postNum){
        $randomList = DB::table('posts')
            ->leftJoin('comments', 'posts.indexPosts', '=', 'comments.postNum')
            ->select('posts.indexPosts', 'posts.image', 'posts.subTitle','posts.title','likeIt',DB::raw('count(comments.postNum) as commentsCount'))
            ->groupBy('posts.indexPosts')
            ->where('posts.indexPosts', '!=', $postNum)
            ->inRandomOrder()->limit(6)
            ->get();

            $data = array(
                'key'=>true,
                'recommendList'=> $randomList
            );

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //메인페이지 최신 글 6개 보내주기
    public function index()
    {
        $post = DB::table('posts')
            ->leftJoin('comments', 'posts.indexPosts', '=', 'comments.postNum')
            ->select('posts.indexPosts', 'posts.image', 'posts.subTitle','posts.title','likeIt',DB::raw('count(comments.postNum) as commentsCount'))
            ->groupBy('posts.indexPosts')
            ->orderBy('indexPosts', 'desc')->limit(6)
            ->get();

        $data = array(
            'key'=>false
        );

        //DB검색해서 가져온 값이 존재하는지 확인
        if(isset($post[0]->indexPosts)){
            $data = array(
                'key'=>true,
                'postInfo'=> $post
            );
        }

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //글 DB저장(관리자)
    public function store(Request $request)
    {
        $title = $request->input('title');
        $subTitle = $request->input('subTitle');
        $category = $request->input('category');
        $contents = $request->input('contents');
        $image = $request->input('image');
        $adminId = $request->input('adminId');

        $store = DB::table('posts')->insertGetId(['adminId' => $adminId, 'image' => 'https://honeytip.p-e.kr/storage/'.$image,
            'contents' => $contents, 'subTitle' => $subTitle, 'category'=> $category,'title'=>$title,'date'=>NOW()]);

        // 좋아요를 위한 테이블
        DB::table('post_like')->insert(['postNum'=> $store]);

        $confirm = DB::table('posts')->where('indexPosts',$store)->first();

        //DB검색해서 가져온 값이 비었는지 확인
        if(empty($confirm->indexPosts)){
            $data = array(
                'key'=>false
            );
        }
        else {
            $data = array(
                'key'=>true,
                'postNum'=>$store
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //글 상세 내용페이지
    public function show($id)
    {
        $post = DB::table('posts')
            ->leftJoin('comments', 'posts.indexPosts', '=', 'comments.postNum')
            ->select('posts.*',DB::raw('count(comments.postNum) as commentsCount'))
            ->groupBy('posts.indexPosts')
            ->where('indexPosts', $id)
            ->orderBy('indexPosts', 'desc')
            ->first();

        $data = array(
            'key'=>false,
        );

        //DB검색해서 가져온 값이 존재하는지 확인
        if(isset($post->indexPosts)){
            $data = array(
                'key'=>true,
                'postInfo'=> $post
            );
        }

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //수정하려는 글에 대한 정보 전달
    public function edit($id)
    {
        $post = DB::table('posts')->where('indexPosts', $id)->first();

        if(empty($post->indexPosts)){
            $data = array(
                'key'=>false
            );
        }
        else{
            $data = array(
                'key'=>true,
                'postInfo'=> array (
                    'title'=>$post->title,
                    'category'=>$post->category,
                    'contents'=>$post->contents,
                    'image'=>$post->image,
                    'subTitle'=>$post->subTitle
                )
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //글 수정(관리자) 수정되었는지 확인할 수 없어서 true/false 무조건 true 전송 (추후 찾아볼 예정)
    public function update(Request $request, $id)
    {
        $title = $request->input('title');
        $subTitle = $request->input('subTitle');
        $category = $request->input('category');
        $contents = $request->input('contents');
        $image = $request->input('image');

        if($image==="false"){
            DB::table('posts')->where('indexPosts', $id)->update(['contents' => $contents, 'subTitle' => $subTitle, 'category'=> $category,'title'=>$title]);
        }
        else{
            DB::table('posts')->where('indexPosts', $id)->update(['image' => 'https://honeytip.p-e.kr/storage/'.$image,
                'contents' => $contents, 'subTitle' => $subTitle, 'category'=> $category,'title'=>$title]);
        }

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //글 삭제(관리자)
    public function destroy($id)
    {
        $confirm = DB::table('posts')->where('indexPosts',$id)->first();

        //DB검색해서 가져온 값이 비었다. 이미 삭제됨
        if(empty($confirm->indexPosts)){
            $data = array(
                'key'=>false
            );
            return json_encode($data,JSON_UNESCAPED_UNICODE);
        }

        DB::table('posts')->where('indexPosts', $id)->delete();

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}
