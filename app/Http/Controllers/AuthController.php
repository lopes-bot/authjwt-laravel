<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validador;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api',['except'=>['login','register']]);
    }
    public function login(Request $request){
        $validator = Validador::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|string|min:6',
        ]);
        if($validator->fails()):
            return response()->json($validator->errors(),400);
        endif;

        $token_validity = 24 * 60;
        $this->guard()->factory()->setTTL($token_validity);

        if(!$token = $this->guard()->attempt($validator->validated())):
            return response()->json(['error'=>'Unaunthorized'],401);
        endif;

        return $this->respondWithToken($token);
    }
    public function register(Request $request){


    }
    public function logout(){

    }

    public function profile(){

    }
    public function refresh(){

    }
    protected function respondWithToken($token){
        return response()->json([
            'token'=> $token,
            'token_type'=> 'bearer',
            'token_validity'=> $this->guard()->factory()->getTTL() * 60
        ]);
    }
    protected function guard(){
        return Auth::guard();
    }
}
