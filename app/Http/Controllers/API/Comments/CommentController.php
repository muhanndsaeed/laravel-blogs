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

        /**
     * @OA\Get(
     *      path="/comment/{post_id}",
     *      operationId="getmycomment",
     *      tags={"Comment"},
     *      summary="As a user I can show all my comments",
     *          @OA\Parameter(
     *          name="post_id",
     *          description="Post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *         name="bearerAuth",
     *         in="header",
     *         required=true,
     *         description="Bearer {access-token}",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *       @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         description="application/json",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="success", type="boolean", example=true),
    *              @OA\Property(property="message", type="object", 
    *                       @OA\Property(property="id", type="number", example=8),
    *                       @OA\Property(property="user_id", type="number", example=2),
    *                      @OA\Property(property="post_id", type="number", example=1),
    *                      @OA\Property(property="user_name", type="string", example="Muhnnd"),
    *                      @OA\Property(property="name", type="string", example="Muhnnd"),
    *                      @OA\Property(property="email", type="string", example="muhnd@exmaple.com"),
    *                      @OA\Property(property="content", type="string", example="Good post"),
    *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),
    *              ),
    *          )
    *      ),
    *      @OA\Response(
     *          response=404,
     *          description="Not Found Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="message", type="string", example="you dont have any comment"),
    *          )
    *      ),
     *       ),
     *       ),
     *     )
     */

 
    public function index(Comment $comment)
    {
        try{
            $comments = Comment::where('user_id',auth()->id())->get();
            if(count($comments)){
                return CommentResource::collection($comments);
            }else{
                return $this->handleError("you dont have any comment",404);
            }
        }catch (Exception $error) {
            return $this->handleError($error,500);
        }  
    }

   /**
     * @OA\Post(
     *      path="/comment",
     *      operationId="addcomment",
     *      tags={"Comment"},
     *      summary="As a user I can add comment on post",
     *      @OA\Parameter(
     *         name="bearerAuth",
     *         in="header",
     *         required=true,
     *         description="Bearer {access-token}",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *       @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         description="application/json",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"post_id","content"},
     *            @OA\Property(property="post_id", type="number", format="string", example=1),
     *            @OA\Property(property="content", type="string", format="string", example="Good post "),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="success", type="boolean", example=true),
    *              @OA\Property(property="data", type="object", 
    *                      @OA\Property(property="post_id", type="number", example=1),
    *                      @OA\Property(property="content", type="string", example="Good post"),
    *                       @OA\Property(property="user_id", type="number", example=2),
    *                          @OA\Property(property="updated_at", type="string", example="2024-06-28 06:06:17"),
    *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),
    *                       @OA\Property(property="id", type="number", example=8),
    *              ),
     *                  @OA\Property(property="message", type="string", example="Added Comment Success!"),
    *          )
    *      ),
    *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="message", type="string", example="Unauthenticated"),
    *          )
    *      ),
     *       ),
     *       ),
     *     )
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
    
    /**
     * @OA\Get(
     *      path="/comment/{id}",
     *      operationId="showcomment",
     *      tags={"Comment"},
     *      summary="As a user I can show all comments on the post",
     *          @OA\Parameter(
     *          name="id",
     *          description="Post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *         name="bearerAuth",
     *         in="header",
     *         required=true,
     *         description="Bearer {access-token}",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *       @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         description="application/json",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="success", type="boolean", example=true),
    *              @OA\Property(property="data", type="object", 
    *                       @OA\Property(property="id", type="number", example=8),
    *                       @OA\Property(property="user_id", type="number", example=2),
    *                      @OA\Property(property="post_id", type="number", example=1),
    *                      @OA\Property(property="user_name", type="string", example="Muhnnd"),
    *                      @OA\Property(property="name", type="string", example="Muhnnd"),
    *                      @OA\Property(property="email", type="string", example="muhnd@exmaple.com"),
    *                      @OA\Property(property="content", type="string", example="Good post"),
    *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),
    *              ),
     *                  @OA\Property(property="message", type="string", example="Added Comment Success!"),
    *          )
    *      ),
    *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="message", type="string", example="Unauthenticated"),
    *          )
    *      ),
     *       ),
     *       ),
     *     )
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
     * As a user I can update on my comment.
     */

        /**
     * @OA\PUT(
     *      path="/comment/{comment_id}",
     *      operationId="updatecomment",
     *      tags={"Comment"},
     *      summary="As a user I can update on my comment",
     *         @OA\Parameter(
     *          name="commnt_id",
     *          description="Comment ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *         name="bearerAuth",
     *         in="header",
     *         required=true,
     *         description="Bearer {access-token}",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *       @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         description="application/json",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"content"},
     *            @OA\Property(property="content", type="string", format="string", example="Good post "),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="success", type="boolean", example=true),
    *              @OA\Property(property="data", type="object", 
    *                       @OA\Property(property="id", type="number", example=8),
     *                       @OA\Property(property="user_id", type="number", example=2),
    *                      @OA\Property(property="post_id", type="number", example=1),
    *                      @OA\Property(property="content", type="string", example="Good post"),
    *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),
    *                          @OA\Property(property="updated_at", type="string", example="2024-06-28 06:06:17"),
    *              ),
    *                  @OA\Property(property="message", type="string", example="Update comment successfully"),
    *          )
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="message", type="string", example="Unauthenticated"),
    *          )
    *      ),
     *       ),
     *       ),
     *     )
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
     *As a user I can delete my comment on the post
     */

      /**
     * @OA\Delete(
     *      path="/comment/{comment_id}",
     *      operationId="deletecomment",
     *      tags={"Comment"},
     *      summary="As a user I can update on my comment",
     *         @OA\Parameter(
     *          name="commnt_id",
     *          description="Comment ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *         name="bearerAuth",
     *         in="header",
     *         required=true,
     *         description="Bearer {access-token}",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *       @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         description="application/json",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="success", type="boolean", example=true),
    *              @OA\Property(property="data", type="object", 
    *                       @OA\Property(property="id", type="number", example=8),
     *                       @OA\Property(property="user_id", type="number", example=2),
    *                      @OA\Property(property="post_id", type="number", example=1),
    *                      @OA\Property(property="content", type="string", example="Good post"),
    *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),
    *                          @OA\Property(property="updated_at", type="string", example="2024-06-28 06:06:17"),
    *              ),
    *                  @OA\Property(property="message", type="string", example="deleted successfully"),
    *          )
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="message", type="string", example="Unauthenticated"),
    *          )
    *      ),
     *       ),
     *       ),
     *     )
     */
    public function destroy(string $id)
    {
        try{
        $comment = Comment::find($id);
        if(auth()->id() != $comment->user_id){
            return $this->handleError("unauthorised",401);

        }else{
            if($comment){
                $comment -> delete();
                return $this->handleSuccessWithResult($comment ,'deleted successfully');
            }else{
                return $this->handleError("the comment not found",401);
                }
        }
        }catch (Exception $error) {
            return $this->handleError($error,500);
        }    
    }
}
