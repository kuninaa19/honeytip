<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReplyController extends Controller
{
    public function index()
    {
        return '[' . __METHOD__ . '] ' . 'respond the index page';
    }

    public function create()
    {
        return '[' . __METHOD__ . '] ' . 'respond a create form';
    }

    public function store(Request $request)
    {
        return '[' . __METHOD__ . '] ' . 'validate the form data from the create form and create a new instance';
    }

    public function show($id)
    {
        return '[' . __METHOD__ . '] ' . 'respond an instance having id of ' . $id;
    }

    public function edit($id)
    {
        return '[' . __METHOD__ . '] ' . 'respond an edit form for id of ' . $id;
    }

    public function update(Request $request, $id)
    {
        return '[' . __METHOD__ . '] ' . 'validate the form data from the edit form and update the resource having id of ' . $id;
    }

    public function destroy($id)
    {
        return '[' . __METHOD__ . '] ' . 'delete resource ' . $id;
    }
}
