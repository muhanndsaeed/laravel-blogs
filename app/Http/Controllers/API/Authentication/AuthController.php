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
            'user_name'=> 'required|string',
            'email'=>'required|email|unique:users,email',
            'phone_number'=> 'required|digits:10',
            'password'=>'required|min:6|max:100',
            'confirm_password'=>'required|same:password',
        ]);
        if($validator->fails()){
            return $this->handleError($validator->errors());
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['name']= $user->user_name;
        $success['email']= $user->email;
        $success['phone_number']= $user->phone_number;

        return $this->handleResponse($success,'User successfully registered');
    }
}
