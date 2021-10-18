<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
Use App\VerifyUser;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Response;
use Validator;
use Auth;
use Carbon\Carbon;


class UserController extends Controller
{

    public function signup(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verified' => 1
        ]);
        $user->save();

        VerifyUser::create([
            'user_id' => $user->id,
            'token' => sha1(time())
        ]);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }


    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user'   => $user,
            'access_token' => $accessToken,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        #for api route
        if (\Request::is('api*')) {
            
            $data = User::orderBy('id','DESC')->get();
            return Response::json(['error'=>'false', 'data'=>$data]);
        }
        else{
            #for web route
            $data = User::orderBy('id','DESC')->paginate(5);
            return view('admin.users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
        }

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $roles = Role::pluck('name','name')->all();
        return view('admin.users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $rules = [ 
        //     'name' => 'required',
        //     'email' => 'required|email|unique:users,email',
        //     'password' => 'required|same:confirm-password',
        //     'roles' => 'required'
        // ];

        // $validator = Validator::make($request->all(), $rules);

        // if($validator->passes())
        // {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['verified'] = 1;


            $user = User::create($input);
            $user->assignRole($request->input('roles'));

            VerifyUser::create([
                'user_id' => $user->id,
                'token' => sha1(time())
            ]);

            if($user)
            {
                #for api&web route handlig
                if (\Request::is('api*')) {
                    return Response::json(['error'=>'false', 'msg'=>'User added successfully']);
                }else{
                    #for web route
                    return redirect()->route('users.index')
                        ->with('success','User created successfully');
                }  
            }
            else
            {
                return Response::json(['error'=>'true', 'msg'=>'something went wrong try again']);
            } 
        // }
        // else
        // {
        //     return Response::json(['error'=>'true', 'msg'=>'required fields are missing']);

        // }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $user = User::find($id);
        #for api route
        if (\Request::is('api*')) {
            return Response::json(['error'=>'false', 'data'=>$user]);
        }
        else{
            return view('admin.users.show',compact('user'));

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();


        return view('admin.users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }


        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();


        $user->assignRole($request->input('roles'));


        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        #for api route
        if (\Request::is('api*')) {
            $user = User::find($id);
            if(!$user)
            {
                return Response::json(['error'=>'true', 'msg'=>'No such user found']);
            }

            $user->delete();
            return Response::json(['error'=>'false', 'msg'=>'User deleted successfully']);
        }
        else{
            $user = User::find($id);
            if(!$user)
            {
                abort(404);
            }

            $user->delete();
            return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
        }
        
    }
}
