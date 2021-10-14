<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Auth;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Cart;

class CustomerController extends Controller
{
    public function customerLoginRegister()
    {
        return view('customers.login-register');
    
    }

    #new customer registration
    public function register(Request $request)
    {
        $this->validate($request, [

            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]); 

        $data = $request->all();
        if($request->isMethod('post'))
        {
            $userCount = Customer::where('email', $request->email)->count();
            if($userCount > 0)
            {
                return redirect()->back()->with('flash_message_error', 'Email Already Exists.');
            }
          else
          {
            //create new customer
            $request["password"] = md5($request->password);
            Customer::create($request->all());

            //Send Confirmation Email
            $email = $data['email'];
            $messageData = ['email'=>$data['email'], 'name'=>$data['name'], 'code'=>base64_encode($data['email'])];
            Mail::send('emails.confirmation',$messageData,function($message) use($email){
               $message->to($email)->subject('Confirm your account');
            });

            return redirect()->back()->with('flash_message_error', 'An Email is sent to your account, please confirm your email to activate your account');
          }
        }
    }

    //activate account
    public function confirmAccount($email)
    {
        $email = base64_decode($email);
        $customerCount = Customer::where('email', $email)->count();

        if($customerCount > 0){
             $customer = Customer::where('email', $email)->first();
             if($customer->verified == 0){
              Customer::where('email',$email)->update(['verified'=>1]);

              return redirect('/login-register')->with('flash_message_success', 'Your account has been activated. You can login now.');
             }
             else{
              return redirect('/login-register')->with('flash_message_success', 'Your account is already active. You can login.');
             }
        }
        else{
          abort(404);
        }
    }

    #customer login
    public function login(Request $request)
    {
        if($request->isMethod('post'))
        {
            $data = $request->input();
            $customer_status = Customer::where('email', $data['email'])->first();
            
            if($customer_status->verified == 0)
            {
                return back()->with('flash_message_error', 'Your account is not active ! please confirm your email to activate your account.');
            }

            $customerCount = Customer::where(['email'=> $data['email'], 'password'=> md5($data['password'])])->count();

           if($customerCount > 0)
           {
                Session::put('customerSession', $data['email']);
                if(Session::has('session_id'))
                {
                    $session_id = Session::get('session_id');
                    Cart::where('session_id', $session_id)->update(['user_email'=>$data['email']]);
                }
                return redirect('/cart');
            }
            else{
                return back()->with('flash_message_error', 'Invalid Username or Password.');
            }
        }
    }

    #logout
    public function logout()
    {
        Session::flush();
        return redirect('/login-register')->with('flash_message_success', 'Logged Out Successfully.');
    }
}
