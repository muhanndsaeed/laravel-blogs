<?php

namespace App\Http\Controllers\API\Authentication;



use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\UserResource;


class UserController extends BaseController
{
     /**
     * @OA\Post(
     *      path="user/updatePassword",
     *      operationId="updatePassword",
     *      tags={"Authentication"},
     *      summary="as a user i can change my password",
     *      @OA\Parameter(
     *         name="bearerAuth",
     *         in="header",
     *         required=true,
     *         description="Bearer {access-token}",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *      @OA\Parameter(
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
     *            required={"password","new_password","new_password_confirmation"},
     *            @OA\Property(property="password", type="string", format="string", example="Garage1234"),
     *            @OA\Property(property="new_password", type="string", format="string", example="Garage12345"),
     *            @OA\Property(property="new_password_confirmation", type="string", format="string", example="Garage12345"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Your current password is change",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Your current password is incorrect",
     *       ),
     *      @OA\Response(
     *          response=402,
     *          description="Some error happened , please try again",
     *       ),
     *     )
     */
    public function updatePassword(Request $request){
        $user = auth() -> user();
        $validatedData = $request -> validate([
            'password'=> 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        if(!Hash::check($request -> password , $user -> password)){
            return $this->handleError('Your current password is incorrect',401);
        }

        if($validatedData){
            $user -> password =bcrypt($validatedData['new_password']);
            if($user -> save()){
               return $this->handleSuccess('Your current password is change');
    
            }else{
                return $this->handleError('Some error happened , please try again',402);
            }
        }
    }

     /**
     * @OA\Post(
     *      path="user/profile/edit",
     *      operationId="updateProfile",
     *      tags={"Authentication"},
     *      summary="as a user i can update on my profile",
     *      @OA\Parameter(
     *         name="bearerAuth",
     *         in="header",
     *         required=true,
     *         description="Bearer {access-token}",
     *         @OA\Schema(
     *              type="String"
     *         ) 
     *      ), 
     *      @OA\Parameter(
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
     *            required={"name"},
     *            @OA\Property(property="name", type="string", format="string", example="Garage"),
     *            @OA\Property(property="phone_number", type="string", format="string", example="0555555555"),
     *            @OA\Property(property="user_name", type="string", format="string", example="Garage23"),
     *            @OA\Property(property="email", type="string", format="string", example="Garage@gmail.com"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Update successfully",
     *       ),
     *      @OA\Response(
     *          response=402,
     *          description="Some error happened , please try again",
     *       ),
     *     )
     */
    public function updateProfile(Request $request){

        $validatedData = $request -> validate([
            'name'=> 'required|string',
            'phone_number'=> 'string',
            'user_name'=> 'unique:users,user_name,'.auth()->id(),
            'email'=> 'unique:users,email,'.auth()->id()
        ]);
        if(auth()->user()->update($validatedData)){
            return $this->handleSuccess('Update successfully');
        }else{
            return $this->handleError('Some error happened , please try again',402);
            }
        }

      /**
     * @OA\Get(
     *      path="user/profile",
     *      operationId="profile",
     *      tags={"Authentication"},
     *      summary="as a user i can show my profile",
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
    *          @OA\JsonContent(
    *                  @OA\Property(property="success", type="boolean", example=true),
    *              @OA\Property(property="data", type="object", 
    *                      @OA\Property(property="id", type="number", example=2),
    *                      @OA\Property(property="user_name", type="string", example="User"),
    *                      @OA\Property(property="name", type="string", example="munhd"),
    *                      @OA\Property(property="email", type="string", example="muhnd@example.com"),
    *                      @OA\Property(property="phone_number", type="string", example="0552318312"),
    *                      @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
    *                      @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
      
    *              ),
      *                  @OA\Property(property="message", type="string", example="User fetched successfully!"),
    *          )
    *      ),
     *       ),
     * 
     *      @OA\Response(
     *          response=404,
     *          description="No such Found!"
     *      )
     *     )
     */
    
    public function showMyProfile()
    {
        $user = auth() -> user();
        if($user){
            return $this->handleSuccessWithResult(new UserResource($user),"Profile fetched successfully!");
        }else{
            return $this->handleError('No such Found!',404);
        }
    }

    
    
}
