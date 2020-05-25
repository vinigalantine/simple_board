<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct(Post $model)
    {   
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('post.index', ['posts' => $this->model->whereNull('parent_id')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->model->title = $request->input('title');
        $this->model->body = $request->input('body');

        if($request->input('parent_id'))
            $this->model->parent_id = $request->input('parent_id');

        if($this->model->save())
            return response()->json(["msg" => "Post cadastrado com sucesso!", "id" => $this->model->id]);
        else
            return response()->json(["msg" => "Ops, algo de errado não está certo! :/"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        $this->model = $this->model->find($id);

        $this->model->title = $request->input('title');
        $this->model->body = $request->input('body');

        if($this->model->save())
            return response()->json(["msg" => "Post alterado com sucesso!"]);
        else
            return response()->json(["msg" => "Ops, algo de errado não está certo! :/"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->model = $this->model->find($id);

        if($this->model->delete())
            return response()->json(["msg" => "Post deletado com sucesso!"]);
        else
            return response()->json(["msg" => "Ops, algo de errado não está certo! :/"]);
    }
}
