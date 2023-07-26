<?php

namespace App\Http\Controllers\API\Authentication;



use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;

class UserController extends BaseController
{
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
                return $this->handleError('Some error happened , please try again',401);
            }
        }
    }

    public function updateProfile(Request $request){

        $validatedData = $request -> validate([
            'name'=> 'required|string',
            'user_name'=> 'unique:users,user_name,'.auth()->id(),
            'email'=> 'unique:users,email,'.auth()->id()
        ]);
        
        if(auth()->user()->update($validatedData)){
            return $this->handleSuccess('Update successfully');
        }else{
            return $this->handleError('Some error happened , please try again',401);
            }
        }
}
