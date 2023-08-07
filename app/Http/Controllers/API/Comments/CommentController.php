<?php

namespace App\Http\Controllers\API\Comments;

use Exception;
use App\Models\Post;
use App\Models\Comment;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Controllers\API\BaseController;

class CommentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        
        try {
            $post = Post::find($request->post_id);
            if($post){
            $request['user_id']= auth()->id();
            $comment = $post->comment()->create($request->all());
            return $this->handleSuccessWithResult($comment,'Added Comment Success!');
            } else {
                return $this->handleError('Post not found!',404);
            }
        } catch (Exception $error) {
            //throw $th;
            return $this->handleError($error,500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
