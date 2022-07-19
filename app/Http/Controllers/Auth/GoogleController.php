<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Socialite;
use Auth;
use Exception;
use App\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }     

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        
        try {
            $user = Socialite::driver('google')->user();    
            $finduser = User::where('google_id', $user->id)->first();    
            if($finduser){  

                Auth::login($finduser);  
                return redirect('/admin');  

            }else{

                $newUser = User::create([
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'google_id' => $user->id,
                    'user_type' =>2,
                    'satatus'   =>1
                ]);   
                Auth::login($newUser);    
                return redirect('/admin');
            }

        } catch (Exception $e) {
            print_r($e->getMessage());
        }

    }
}
