<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Socialite;
use App\VerifyUser;
use App\SocialProvider;
use Mail;
use App\Mail\VerifyMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Response;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    #verify user with email functions
    protected function create(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => sha1(time())
        ]);
        \Mail::to($user->email)->send(new VerifyMail($user));
        return $user;
    }


    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if(isset($verifyUser) )
        {
            $user = $verifyUser->user;
            if(!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                $status = "Your e-mail is verified. You can now login.";
            }
            else 
            {
                $status = "Your e-mail is already verified. You can now login.";
            }
        } 
        else 
        {
            return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
        }
        return redirect('/login')->with('status', $status);
    }

    protected function registered(Request $request, $user)
    {
      $this->guard()->logout();
      return redirect('/login')->with('status', 'We sent you an activation code. Check your email and click on the link to verify.');
    }


    #google login functions
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try
        {
            $socialUser = Socialite::driver('google')->user();
        }
        catch(\Exception $e)
        {
            return redirect('/');
        }
        //check if we have logged provider
        $socialProvider = SocialProvider::where('provider_id',$socialUser->getId())->first();
        if(!$socialProvider)
        {
            //create a new user and provider
            $user = User::firstOrCreate([
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName(),
                'verified' => 1
            ]);

            $user->socialProviders()->create(
                ['provider_id' => $socialUser->getId(), 'provider' => 'google']
            );

        }
        else
            $user = $socialProvider->user;

        auth()->login($user);

        return redirect('/users');
    }

    #facebook login functions
    public function facebookRedirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function facebookCallback($provider)
    {
       $getInfo = Socialite::driver($provider)->user(); 
       $user = $this->createUser($getInfo,$provider); 
       auth()->login($user); 
       return redirect()->to('/home');
    }

    function createUser($getInfo,$provider)
    {
        $user = SocialProvider::where('provider_id', $getInfo->id)->first();
        if (!$user)
        {

            #create a new user and provider
            $user = User::firstOrCreate([
                'email' =>  $getInfo->getEmail(),
                'name' =>  $getInfo->getName(),
                'verified' => 1
            ]);

            $user->socialProviders()->create(
                ['provider_id' => $getInfo->id, 'provider' => $provider]
            );
        }
       return $user;
    }
}
