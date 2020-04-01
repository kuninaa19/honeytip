<?php
// 1. store() 댓글 DB저장
// 2. edit () 댓글 수정하기위한 작성된 댓글 내용가져오기
// 3. update() 댓글 수정
// 4. destroy() 댓글 삭제
// 5. comments_list() 댓글 리스트 목록 (페이징)

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    //댓글 DB저장하기
    public function store(Request $request)
    {
        //
    }

    // 댓글 수정하기위한 작성된 댓글 내용가져오기
    public function edit($id)
    {
        //
    }

    // 댓글 수정하기 버튼 누르고 DB에 수정된 댓글 저장
    public function update(Request $request, $id)
    {
        //
    }

    // 댓글 삭제
    public function destroy($id)
    {
        //
    }

    // 댓글 리스트 목록 (페이징)
    // 파라미터 글 번호 , 댓글 페이징 번호
    public function comments_list($num,$page)
    {
        return ;
    }
}
