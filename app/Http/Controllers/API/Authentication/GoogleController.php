<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Auth;

class GoogleController extends BaseController
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
      
            $user = Socialite::driver('google')->stateless()->user();
       
            $finduser = User::where('google_id', $user->id)->first();
       
            if($finduser){
       
                return 'You login';
       
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('my-google')
                ]);
      
                return 'you login new user';
            }
      
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    
    }
}
