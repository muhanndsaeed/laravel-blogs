<?php

namespace App\Http\Controllers\API\Post;

use Exception;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Controllers\API\BaseController;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        try{
            return PostResource::collection(Post::all());
        }catch (Exception $error) {
            return $this->handleError($error,500);
        }    
    }

 

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {   
        try{
            $category = Category::find($request->category_id);
            if($category){
                $request['user_id'] = auth() -> id();
                $post = $category -> post() -> create($request->all());
                return $this->handleSuccessWithResult($post ,'add successfully');
            }else{
                return $this->handleError("the category not found",401);
                }
        }catch (Exception $error) {
            return $this->handleError($error,500);
        }     
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $post =  Post::where('category_id',$id)->get();
            if(count($post)){
                return $this->handleSuccessWithResult($post,'Posts retrieved successfully');
            }else {
                return $this->handleError("the category not found",404);
            }
        } catch (Exception $error) {
            //throw $th;
            return $this->handleError($error,500);
        }

    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Post $post)
    {
        $post = Post::find($post -> id);
        if(auth()-> id() != $post -> user_id){
            return $this->handleError("You don't own post",401);
        }else{
            try{
                if($post){
                    $post = $post -> update($request->all());
                    return $this->handleSuccessWithResult($post ,'update successfully');
                }else{
                    return $this->handleError("the post not found",401);
                    }
            }catch (Exception $error) {
                return $this->handleError($error,500);
            }     
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $userId = auth()->user()->id;
        $post = Post::where('user_id',$userId)->where('id',$id)->first();
        try {
            if(!empty($post)){
                $post->delete();
                return $this->handleSuccess('Post deleted successfully');
            }else {
                return $this->handleError('Post Not Found',404);
            }
        } catch (\Throwable $th) {
            return $this->handleError($th,500);
        }
        

    }
}
