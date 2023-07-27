<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class AuthController extends BaseController
{
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


    


}
