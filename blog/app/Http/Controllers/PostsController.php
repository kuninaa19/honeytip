<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    //글 상세내용 페이지
    public function post_list($id,$ida){
        return  $id.$ida;
    }

    // 글 생성시 관리자가 작성한 글몇개인지 확인
    public function create()
    {
        return '[' . __METHOD__ . '] ' . 'respond a create form';
    }

    //글 생성(관리자)
    public function store(Request $request)
    {
        return '[' . __METHOD__ . '] ' . 'validate the form data from the create form and create a new instance';
    }

    //글 리스트 목록
    public function show($id)
    {
//        return '[' . __METHOD__ . '] ' . 'respond an instance having id of ' . $id;
        return $id;
    }

    //글 수정(관리자)
    public function update(Request $request, $id)
    {
        return '[' . __METHOD__ . '] ' . 'validate the form data from the edit form and update the resource having id of ' . $id;
    }

    //글 삭제(관리자)
    public function destroy($id)
    {
        return '[' . __METHOD__ . '] ' . 'delete resource ' . $id;
    }
}
