<?php
// imagestore() 글생성 전 이미지 저장
// store() 글 생성
// update() 글 수정
// destroy() 글 삭제
// show () 글 리스트 목록 가져오기(카테고리별)
// post_list() 글 상세내용 페이지

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

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
        $post = DB::table('posts')->where(['index_posts', $id])->first();

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
        DB::table('posts')->where('index_posts', $id)->delete();

        $confirm = DB::table('posts')->where('index_posts',$id)->first();

        //DB검색해서 가져온 값이 비었는지 확인 else 삭제 안됨
        if(empty($confirm->index_posts)){
            $data = array(
                'key'=>true
            );
        }
        else{
            $data = array(
                'key'=>false
            );
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //글 리스트 목록 (페이징)
    public function show($id)
    {
        $admin = DB::table('posts')->where(['category', $id])->get();

        $users = DB::table('users')->where('category', $id)->paginate(15);

        $users = User::where('votes', '>', 100)->paginate(15);

        return view('user.index', ['users' => $users]);


        if(empty($confirm->index_posts)){
            $data = array(
                'key'=>false
            );
//객체를 문자열형태로 만들기
            $value = json_encode($data);
            return $value;

//          return response()->json($data);
        }
        else{
            $key = true;

            $data = array(
                'key'=>$key,
            );
            return json_encode($data,JSON_UNESCAPED_UNICODE);
//            return response()->json($data);
        }
    }

    //글 상세 내용페이지
    public function  content($category,$num){
        $post = DB::table('posts')->where([['category', $category], ['index_posts', $num]])->first();

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

    //이미지 저장후 주소 전달
    public function imageStore(Request $request){
        $image_path = $request->file('fileToUpload')->store('images','public');

        return $image_path;
    }

    //글 상세내용 페이지 (view +1 증가)
    public function post_list($category,$num){

        return  $category.$num;
    }
}
