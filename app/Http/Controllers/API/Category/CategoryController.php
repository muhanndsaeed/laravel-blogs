<?php

namespace App\Http\Controllers\API\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\API\BaseController;


class CategoryController extends BaseController
{

    /**
     * @OA\Get(
     *      path="/category",
     *      operationId="getCategory",
     *      tags={"Category"},
     *      summary="Get list of Category",
     *      description="Returns list of categorities",
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="object", 
     *              @OA\Property(property="id", type="number", example=2),
     *               @OA\Property(property="user_id", type="number", example=3),
     *                      @OA\Property(property="title", type="string", example="Sport"),
     *                      @OA\Property(property="description", type="string", example="Sport .."),  
     *                          @OA\Property(property="updated_at", type="string", example="2024-06-28 06:06:17"),
     *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),
     *              ),
     *                  @OA\Property(property="message", type="string", example="Categories retrieved successfully"),
     *          )
     *      ),

     *     )
     */
    public function index(){

        $categories = Category::all();
        if (!empty($categories)){
            return $this->handleSuccessWithResult($categories,'Categories retrieved successfully');
        }else{
            return $this->handleError('No Records Found',200); 
        }
    }

    /**
     * @OA\Post(
     *      path="/category",
     *      operationId="category",
     *      tags={"Category"},
     *      summary="as a admin i can add category",
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
     *            required={"title","description"},
     *            @OA\Property(property="title", type="string", format="string", example="Sport"),
     *            @OA\Property(property="description", type="string", format="string", example="Sport .. "),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="success", type="boolean", example=true),
    *              @OA\Property(property="data", type="object", 
    *                      @OA\Property(property="title", type="string", example="Sport"),
    *                      @OA\Property(property="description", type="string", example="Sport .."),  
    *              ),
      *                  @OA\Property(property="message", type="string", example="Create category successfully"),
    *          )
    *      ),
    *      @OA\Response(
     *          response=401,
     *          description="Unauthorized Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="message", type="string", example="Unauthorized"),
    *          )
    *      ),
     *       ),
     *       ),
     *     )
     */

    public function store(CategoryRequest $request){

        try {
        
            Category::create([
                'user_id'=> Auth::user()->id,
                'title'=>$request->title,
                'description'=>$request->description,
            ]);
            $success = $request->all();
            
            return $this->handleSuccessWithResult($success,'Create category successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->handleError($th,500);
        }
        

    }


    /**
     * @OA\PUT(
     *      path="/category/{id}",
     *      operationId="updatecategory",
     *      tags={"Category"},
     *      summary="as a admin i can update on category",
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
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
     *            required={"title","description"},
     *            @OA\Property(property="title", type="string", format="string", example="Sport"),
     *            @OA\Property(property="description", type="string", format="string", example="Sport .. "),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="object", 
     *              @OA\Property(property="id", type="number", example=2),
     *               @OA\Property(property="user_id", type="number", example=3),
     *                      @OA\Property(property="title", type="string", example="Sport"),
     *                      @OA\Property(property="description", type="string", example="Sport .."),  
     *                          @OA\Property(property="updated_at", type="string", example="2024-06-28 06:06:17"),
     *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),
     *              ),
     *                  @OA\Property(property="message", type="string", example="Update category successfully"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized Response",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Unauthorized"),
     *          )
     *      ),
     *       ),
     *       ),
     *     )
     */

    public function update(CategoryRequest $request,$id){

       $category = Category::find($id);
        if($category){
            $category->update([
                'user_id'=> Auth::user()->id,
                'title'=>$request->title,
                'description'=>$request->description,
       ]);

       return $this->handleSuccessWithResult($category,'Update category successfully');

        } else{
            return $this->handleError("Category not found",404);
        }
    
    }

        /**
     * @OA\DELETE(
     *      path="/category/{id}",
     *      operationId="deletecategory",
     *      tags={"Category"},
     *      summary="as a admin i can delete category",
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
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
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="object", 
     *              @OA\Property(property="id", type="number", example=2),
     *               @OA\Property(property="user_id", type="number", example=3),
     *                      @OA\Property(property="title", type="string", example="Sport"),
     *                      @OA\Property(property="description", type="string", example="Sport .."),  
     *                          @OA\Property(property="updated_at", type="string", example="2024-06-28 06:06:17"),
     *                     @OA\Property(property="created_at", type="string", example="2023-06-28 06:06:17"),
     *              ),
     *                  @OA\Property(property="message", type="string", example="deleted successfully"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized Response",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Unauthorized"),
     *          )
     *      ),
     *       ),
     *       ),
     *     )
     */

    public function destroy(string $id)
    {   
        $categories = Category::find($id);
        
        if($categories){
            $categories -> delete();
            return $this->handleSuccessWithResult($categories ,'deleted successfully');
        }else{
            return $this->handleError("the category not found",401);
            }
    }
    
    
}
