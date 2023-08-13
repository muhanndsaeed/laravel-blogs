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
use App\Http\Resources\CommentResource;

class CommentController extends BaseController
{
    /**
     * As a user I can show all my comments.
     */
    public function index(Comment $comment)
    {
        try{
            $comments = Comment::where('user_id',auth()->id())->get();
            if(count($comments)){
                return CommentResource::collection($comments);
            }else{
                return "you dont have any comment";
            }
        }catch (Exception $error) {
            return $this->handleError($error,500);
        }  
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
            return $this->handleError($error,500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $comments = Comment::where('post_id',$id)->get();
            if(count($comments)){
            return $this->handleSuccess(CommentResource::collection($comments));
        }else {
            return $this->handleError("there's no comments",404);
        }
        }  catch (Exception $error) {
            return $this->handleError($error,500);
        }
        
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * As a user I can update on my comment.
     */
    public function update(Request $request,string $id)
    { 
        try{
        $comment = Comment::find($id);
        if(auth()->id() != $comment->user_id){
            return $this->handleError("anauthorised",404);
        }else{
            if($comment){
                $comment->update([
                    'content'=>$request->content,
           ]);
                return $this->handleSuccessWithResult($comment,'Update comment successfully');
            } else{
                return $this->handleError("comment not found",404);
            }
        }  
        }catch (Exception $error) {
            return $this->handleError($error,500);
        }  
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
