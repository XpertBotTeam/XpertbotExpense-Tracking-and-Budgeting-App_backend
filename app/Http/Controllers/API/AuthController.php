<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JWTAuth;



class AuthController extends Controller
{

public function login(Request $request){
        $credentials=$request->only('email','password');
        $credentials= [ 
            'email'=>$request->email,
            'password'=>$request->password
        ];
        if(Auth::attempt($credentials)){
            
            $user=Auth::user();
           $access_token = $user->createToken('authtoken')->plainTextToken;
           return response()->json([
                'status'=>true,
                'token'=>$access_token,
                'message'=>'User logged-in successfully'
            ]);
        }
        else{
            return response()->json([
            'status'=>false,
            'message'=>'wrong username or password '
            ]);
        }
    }

    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'false','message'=>'user already exists']);
        }

        else{
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['status'=>true,'message' => 'User registered successfully', 'token' => $token]);
        }
    }
    public function getUserDetails(Request $request)
{
    $user = $request->user(); 
    return response()->json([
        'username' => $user->name, 
    ]);
}


}

