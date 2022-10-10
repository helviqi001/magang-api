<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ResetPasswordRequest;

class AuthController extends Controller
{
    public function register(Request $request)
    {
    //  $validator = Validator::make($request->all(),[
    //         'name' => 'required|max:255',
    //         'email' => 'required|email',
    //         'no_telp' => 'required',
    //         'password' => 'required|min:8|confirmed',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json($validator->errors());       
    //     }

    //     $customer = Customer::create(array_merge(
    //         $validator->validate(),
    //         ['password'=> Hash::make($request->password)]
    //     ));

    //     return response()->json([
    //         'message'=> 'Successfully registered',
    //         'customer'=> $customer
    //     ], 201);

    $this->validate($request, [
        'name' => 'required|max:255',
        'email' => 'required|email',
        'no_telp' => 'required',
        'password' => 'required|min:8|confirmed',
    ]);

    $name=$request->input('name');
    $email=$request->input('email');
    $no_telp=$request->input('no_telp');
    $password=Hash::make($request->input('password'));

    $customer=Customer::create([
        'name'=>$name,
        'email'=>$email,
        'no_telp'=>$no_telp,
        'password'=>$password
    ]);

    return response()->json([
        'data'=>$customer,
        'message'=>'Data berhasil ditambahkan'
    ]);
    }


    //Controller Login
   public function login(Request $request){
    $this->validate($request,[
        'email'=>'required',
        'password'=>'required'
    ]);

    $email=$request->input('email');
    $password=$request->input('password');

    $customer= Customer::where('email',$email)->first();
    if(!$customer){
        return response()->json(['message'=>'Login gagal'],401);
    }

    $isValidPassword= Hash::check($password, $customer->password);
    if(!$isValidPassword){
        return response()->json(['message'=>'Login gagal'], 401);
    }

    $generateToken= bin2hex(random_bytes(40));
    $customer->update([
        'token'=> $generateToken
    ]);
    return response()->json
    ([$customer,
     'token'=> $generateToken], Response::HTTP_OK);
   }    


   //Controller logout
   public function logout()
    {
        // auth()->logout();
        // return ['message'=>'Berhasil logout!'];
        Auth::logout();
        return response()->json([
            'status'=>'success',
            'message'=>'Logged out successfully'
        ], Response::HTTP_OK);

    }


    public function profile (Request $request){
        return response()->jon([
            'data'=>$request->customer
        ], Response::HTTP_OK);
    }

    public function forgot(){
        $credentials = request()->validate(['email'=>'required|email']);
        Password::sendResetLink($credentials);
        
        return response()->json([
            'message'=>'Reset password link sent on your email id'
        ]);
    }
    
    public function reset() {
        $credentials = request()->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["msg" => "Invalid token provided"], 400);
        }

        return response()->json(["msg" => "Password has been successfully changed"]);
    }
  
}