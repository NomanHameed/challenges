<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User_infos;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
	public function login(Request $request)
	{
		$this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
		
		if(Auth::attempt(['email' => request('email'), 'password' => request('password'), 'user_type' => 1 ])){ 
            $user = Auth::user();
            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->name);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('email', $user->email);

            $user_infos = User_infos::where([['user_id', '=', $user->id], ['meta_name', '=', 'timezone']])->first();
            $user_infos = json_encode($user_infos);
            $user_infos = json_decode($user_infos, true);
            $timezone = env('DEFAULT_TIMEZONE');
            
            if($user_infos){
                $timezone = $user_infos['meta_value'];
                if($timezone){
                    $request->session()->put('timezone', $timezone); 
                }
                
            }

            return redirect()->route('admin');
        
        }
        /*elseif(Auth::attempt(['email' => request('email'), 'password' => request('password'), 'user_type' => 2 ])){
            $user = Auth::user();
            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->name);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('email', $user->email); 	                     
            return redirect()->route('frontend.home');
            
		}*/
        else{ 

            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
		} 
	}
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	protected function authenticated()
	{
		return redirect('/admin');
	}
}
