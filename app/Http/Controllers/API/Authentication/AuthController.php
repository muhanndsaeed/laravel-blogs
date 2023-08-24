<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PasswordResetTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Notifications\PasswordResetNotification;

class AuthController extends BaseController
{
    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="register",
     *      tags={"Authentication"},
     *      summary="as a user i can register by email and password",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name", "user_name", "email" , "phone_number" , "password" , "confirm_password"},
     *            @OA\Property(property="name", type="string", format="string", example="Noura"),
     *            @OA\Property(property="user_name", type="string", format="string", example="Noura19"),
     *            @OA\Property(property="email", type="string", format="string", example="Noura@example.com"),
     *            @OA\Property(property="phone_number", type="string", format="string", example="0555555555"),
     *            @OA\Property(property="password", type="string", format="string", example="Garage1234"),
     *            @OA\Property(property="confirm_password", type="string", format="string", example="Garage1234"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User successfully registered",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="validator error",
     *      ),
     *     )
     */
    
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'required|string',
            'user_name'=> 'required|string',
            'email'=>'required|email|unique:users,email',
            'phone_number'=> 'required|digits:10',
            'password'=>'required|min:6|max:100',
            'confirm_password'=>'required|same:password',
        ]);
        if($validator->fails()){
            return $this->handleError($validator->errors(),404);
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['name']= $user->user_name;
        $success['email']= $user->email;
        $success['phone_number']= $user->phone_number;

        return $this->handleSuccessWithResult($success,'User successfully registered');
    }
    



    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"Authentication"},
     *      summary="as a user i can login by email or username and password",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"password"},
     *            @OA\Property(property="name", type="string", format="string", example="Noura"),
     *            @OA\Property(property="user_name", type="string", format="string", example="Noura19"),
     *            @OA\Property(property="password", type="string", format="string", example="Garage1234"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login successfully",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="invalid credentials",
     *      ),
     *     )
     */
    
    public function login(Request $request){
        $validatedData = $request -> validate([
            'email'=> 'string|exists:users,email',
            'user_name'=> 'string|exists:users,user_name',
            'password' => 'required',
        ]);
        
        //The attempt method accepts an array of key / value pairs as its first argument. The values in the array will be used to find the user in your database table
        if(!auth() -> attempt($validatedData)){
            return $this->handleError('invalid credentials',401);
        }else{
            $result = [
                'user' => auth() -> user(),
                'accessToken' => auth() -> user()->createToken('authToken')->accessToken
            ];
            return $this->handleSuccessWithResult($result,'Login successfully');   
        }
    }

    
        /**
     * @OA\Post(
     *      path="/forgot",
     *      operationId="forgot",
     *      tags={"Authentication"},
     *      summary="as a user i can reset my passsword",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"email"},
     *            @OA\Property(property="email", type="string", format="string", example="muhnd@example.com"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="A code has been sent to your email address",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Incorrect Email Address",
     *      ),
     *     )
     */

    public function forgot(Request $request){
        $user = User::where('email',$request->email)->first();

        if(!$user || !$user->email){
            return $this->handleError("Incorrect Email Address",404);
        }

        $resetPasswordToken = str_pad(random_int(1,9999),4,'0',STR_PAD_LEFT);

        if(!$userPassRest = PasswordResetTokens::where('email',$user->email)->first()){

            PasswordResetTokens::create([
                'email' => $user->email,
                'token'=> $resetPasswordToken,
            ]);

        }
        else {
            $userPassRest->update([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        }

        $user->notify( new PasswordResetNotification($resetPasswordToken));

        return $this->handleSuccess('A code has been sent to your email address');
        
    }

        /**
     * @OA\Post(
     *      path="/reset",
     *      operationId="reset",
     *      tags={"Authentication"},
     *      summary="as a user i can reset my passsword",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"email","token","password","confirm_password"},
     *            @OA\Property(property="email", type="string", format="string", example="muhnd@example.com"),
     *            @OA\Property(property="token", type="string", format="string", example="1234"),
     *            @OA\Property(property="password", type="string", format="string", example="Garage1234"),
     *            @OA\Property(property="confirm_password", type="string", format="string", example="Garage1234"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Password Reset Success",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Token mismatch",
     *      ),
     *     )
     */
    public function resetpassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=> 'required|exists:users,email',
            'token'=> 'required',
            'password'=>'required|min:6|max:100',
            'confirm_password'=>'required|same:password',
        ]);
        if($validator->fails()){
            return $this->handleError($validator->errors(),404);
        }
        
        $input = $request->all();
        
        $user = User::where('email',$input['email'])->first();

        $resetRequest = PasswordResetTokens::where('email',$user->email)->first();

        if(!$resetRequest || $resetRequest->token != $request['token']){
            return $this->handleError('Token mismatch',404);
        }

        $user->fill([
            'password'=> Hash::make($input['password']),
        ]);


        $user->save();

        $user->tokens()->delete();

        
        $resetRequest->delete();


        
    
        return $this->handleSuccessWithResult($user,['Password Reset Success']);
        
    }
    
    
    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logout",
     *      tags={"Authentication"},
     *      summary="as a user i can log out",
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
     *      @OA\Response(
     *          response=200,
     *          description="Logged out",
     *       ),
     *     )
     */
    public function logout(Request $request){
        
            $request->user()->tokens()->delete();

            return $this->handleSuccess('Logged out');
        
       
      
    }
    


}
