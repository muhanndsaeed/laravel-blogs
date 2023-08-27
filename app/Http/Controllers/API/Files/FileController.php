<?php

namespace App\Http\Controllers\API\Files;

use Exception;
use App\Models\Files;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\FileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\File;


class FileController extends BaseController
{
    
    /**
     * Store a newly created resource in storage.
     */

  /**
     * @OA\Post(
     *      path="/files",
     *      operationId="addfile",
     *      tags={"Files"},
     *      summary="As a user I can upload file",
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
    * @OA\RequestBody(
    *   request="Files",
    *   required=true,
    *   description="Upload File",
    *   @OA\MediaType(
    *     mediaType="multipart/form-data",
    *       @OA\Schema(
    *        @OA\Property(
    *      property="post_id",
    *       type="number",
    *            ),
    *        @OA\Property(
    *      property="file_name",
    *       type="file",
    *        format="file"
    *            ),
    *    )
    *   )
    * ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful Response",
    *      @OA\JsonContent(
    *              @OA\Property(property="success", type="boolean", example=true),
    *              @OA\Property(property="data", type="object", 
    *                      @OA\Property(property="user_id", type="number", example=2),
    *                      @OA\Property(property="file_name", type="string", example="950_374d1a8979.png"),
    *                      @OA\Property(property="file_path", type="string", example="uploads/vwvE7kZ0UaniOCoSyLitp0krNyIhOQD2OaH6pnbD.png"),
    *                      @OA\Property(property="post_id", type="number", example=1),
    *                          @OA\Property(property="updated_at", type="string", example="2024-06-28 06:06:17"),
    *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),
    *                       @OA\Property(property="id", type="number", example=8),
    *              ),
    *                  @OA\Property(property="message", type="string", example="Added file successfully"),
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
    public function store(FileRequest $request)
    {   
       
        $post= Post::where('user_id',auth()->user()->id)->find($request->post_id);
        if($post){
            $checkFile = $post->file()->where('post_id',$request->post_id)->get();
            if(!count($checkFile)) {
                $name = $request->file_name->getClientOriginalName();
                $path = $request->file_name->store('uploads', 'public');
                $save['user_id'] = auth()->user()->id;
                $save['file_name'] = $name;
                $save['file_path'] = $path;

                $file = $post->file()->create($save);

            return $this->handleSuccessWithResult($file, 'Added file successfully');
            }else {
                return $this->handleError('Post has file already',401);
}
        }else {
            return $this->handleError('Post not found',404);
        }
 
    }

    
    public function DownloadFile(String $id ,Files $file){
        $uploadfile = $file->where('id',$id)->first();
        if($uploadfile){
            $path = $uploadfile->file_path;
            $storedFile = Storage::disk('public')->get($path);
      
            return  response($storedFile, 200)->header('Content-Type', Storage::mimeType($path));
        }else {
            return $this->handleError("File Not Found",404);
        }
        
    }

     /**
     * As a user I can update file
     */


    /**
     * @OA\Post(
     *      path="/files/{id}",
     *      operationId="updatefile",
     *      tags={"Files"},
     *      summary="As a user I can update file",
     *          @OA\Parameter(
     *          name="file_id",
     *          description="File ID",
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
    * @OA\RequestBody(
    *   request="Files",
    *   required=true,
    *   description="Upload File",
    *   @OA\MediaType(
    *     mediaType="multipart/form-data",
    *       @OA\Schema(
    *        @OA\Property(
    *      property="file_name",
    *       type="file",
    *        format="file"
    *            ),
    *    )
    *   )
    * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="success", type="boolean", example=true),
    *              @OA\Property(property="data", type="boolean",example=true ),
    *                  @OA\Property(property="message", type="string", example="Update file successfully"),
    *          ),
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
    public function update(Request $request,string $id){
        try{
            $file = Files::find($id);

            // return $file->file_path;
            
            if(auth()->id() != $file->user_id){
                return $this->handleError("anauthorised",404);
            }else{
                if($file){
                   File::delete(storage_path().'/app/public/'.$file->file_path);
                   $t= $file->update([
                        'file_name'=>$request->file_name->getClientOriginalName(),
                        'file_path'=>$request->file_name->store('uploads','public'),
               ]);
                    return $this->handleSuccessWithResult($t,'Update file successfully');
                } else{
                    return $this->handleError("file not found",404);
                }
            }
        
        }
            catch (Exception $error) {
                return $this->handleError($error,500);
            }  
    }


    /**
     * As a user I can delete file
     */

     /**
     * @OA\Delete(
     *      path="/files/{id}",
     *      operationId="deletefile",
     *      tags={"Files"},
     *      summary="As a user I can delete file",
     *          @OA\Parameter(
     *          name="file_id",
     *          description="File ID",
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
    *                      @OA\Property(property="user_id", type="number", example=2),
    *                      @OA\Property(property="post_id", type="number", example=1),
    *                      @OA\Property(property="file_name", type="string", example="950_374d1a8979.png"),
    *                      @OA\Property(property="file_path", type="string", example="uploads/vwvE7kZ0UaniOCoSyLitp0krNyIhOQD2OaH6pnbD.png"),

    *                          @OA\Property(property="updated_at", type="string", example="2024-06-28 06:06:17"),
    *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),

    *              ),
    *                  @OA\Property(property="message", type="string", example="Added file successfully"),
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
    public function destroy(Files $file)
    {
        try{
            if(auth()->id() != $file->user_id){
                return $this->handleError("unauthorised",401);
            }else{
                if($file->delete()){
                   $deleted = File::delete(storage_path().'/app/public/'.$file->file_path);
                   if($deleted){
                    return $this->handleSuccessWithResult($file ,'deleted successfully');
                   }
                }else{
                    return $this->handleError("the file not found",401);
                    }
            }
            }catch (Exception $error) {
                return $this->handleError($error,500);
            }    
        
    }
}
