<?php

namespace App\Http\Controllers\API\favorites;

use App\Models\Post;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Http\Controllers\API\BaseController;

class FavoritesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    
    /**
     * As a user I can add favorite on the post.
     */
    public function store(FavoriteRequest $request)
    {
        try{
            $post = Post::find($request->post_id);
            if($post){
                $CheckInFavorite =  Favorite::where('post_id',$request->post_id)->where('user_id',auth()->id())->get();
                if(!count($CheckInFavorite)){
                    $request['user_id'] = auth() -> id();
                    $fav = $post -> favorite() -> create($request->all());
                    return $this->handleSuccessWithResult($post ,'add favorite successfully');
                }
            }else{
                return $this->handleError("the post not found",401);
                }
        }catch (Exception $error) {
            return $this->handleError($error,500);
        }     
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        //
    }
}
