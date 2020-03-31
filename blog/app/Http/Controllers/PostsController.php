<?php
// store() 글 생성
// update() 글 수정
// destroy() 글 삭제
// show () 글 상세페이지
// edit () 글 수정하기위한 작성된 글 내용가져오기
// imagestore() 글생성 전 이미지 저장
// category_list() 카테고리별 글 리스트 목록
// viewUp() 조회수 증가

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Pagination\Paginator;

class PostsController extends Controller
{
    //글 DB저장(관리자)
    public function store(Request $request)
    {
        $title = $request->input('title');
        $subTitle = $request->input('subTitle');
        $category = $request->input('category');
        $contents = $request->input('contents');
        $image = $request->input('image');
        $adminId = $request->input('adminId');

        $store = DB::table('posts')->insertGetId(['admin_id' => $adminId, 'image' => 'https://honeytip.p-e.kr/storage/'.$image,
            'contents' => $contents, 'sub_title' => $subTitle, 'category'=> $category,'title'=>$title,'date'=>NOW()]);

        $confirm = DB::table('posts')->where('index_posts',$store)->first();

        //DB검색해서 가져온 값이 비었는지 확인
        if(empty($confirm->index_posts)){
            $data = array(
                'key'=>false
            );
        }
        else {
            $data = array(
                'key'=>true
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //수정하려는 글에 대한 정보 전달
    public function edit($id)
    {
        $post = DB::table('posts')->where('index_posts', $id)->first();

        if(empty($post->index_posts)){
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
                    'subTitle'=>$post->sub_title
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

        DB::table('posts')->where('index_posts', $id)->update(['image' => 'https://honeytip.p-e.kr/storage/'.$image,
            'contents' => $contents, 'sub_title' => $subTitle, 'category'=> $category,'title'=>$title]);

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //글 삭제(관리자)
    public function destroy($id)
    {
        $confirm = DB::table('posts')->where('index_posts',$id)->first();

        //DB검색해서 가져온 값이 비었다. 이미 삭제됨
        if(empty($confirm->index_posts)){
            $data = array(
                'key'=>false
            );
            return json_encode($data,JSON_UNESCAPED_UNICODE);
        }

        DB::table('posts')->where('index_posts', $id)->delete();

        $data = array(
            'key'=>true
        );
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //글 상세 내용페이지
    public function show($id)
    {
        $post = DB::table('posts')->where( 'index_posts', $id)->first();

        $data = array(
            'key'=>false
        );

        //DB검색해서 가져온 값이 비었는지 확인
        if(isset($post->index_posts)){
            $data = array(
                'key'=>true,
                'postInfo'=> array (
                    'title'=>$post->title,
                    'contents'=>$post->contents,
                    'date'=>$post->date,
                    'viewCounts'=>$post->view_count
                )
            );
        }

        return json_encode($data,JSON_UNESCAPED_UNICODE);
//      return response()->json($data);

    }

    //글 리스트 목록 (페이징)
    public function category_list($category,$num){
        $content = DB::table('posts')->where('category', $category)
            ->orderBy('index_posts', 'desc')->offset(($num-1)*6)->limit(6)->get();

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

    //이미지 저장후 주소 전달
    public function imageStore(Request $request){
        $image_path = $request->file('fileToUpload')->store('images','public');

        return $image_path;
    }

    //글 조회수 +1 증가
    public function viewUp($category,$num){
        return  $category.$num;
    }
}
