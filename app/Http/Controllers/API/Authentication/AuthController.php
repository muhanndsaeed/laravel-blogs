<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;

class AuthController extends BaseController
{
    
   
    public function login(Request $request){
        $validatedData = $request -> validate([
            'email'=> 'required',
            'password' => 'required',
        ]);
        
        //The attempt method accepts an array of key / value pairs as its first argument. The values in the array will be used to find the user in your database table
        if(!auth() -> attempt($validatedData)){
            return $this->handleError('invalid email or password',401);
        }else{
            $result = [
                'user' => auth() -> user(),
                'accessToken' => auth() -> user()->createToken('authToken')->accessToken
            ];
            return $this->handleSuccessWithResult($result,'Login successfully');
            
        }
    }
    


}
