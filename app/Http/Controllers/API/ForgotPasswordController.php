<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function forgot_password(Request $request){
        if(!$this->validEmail($request->email)){
            return response()->json([
                'message'=>'Email not found!'
            ], Response::HTTP_NOT_FOUND);
        }else{
            $this->sendEmail($request->email);
            return response()->json([
                'message'=>'Password reset mail has been sent!'
            ], Response::HTTP_OK);
        }
    }

    public function sendEmail($email){
        $generateToken = $this->createToken($email);
        Mail::to($email)->send(new SendMail($generateToken));
    }

    public function validMail($email){
        return !!Customer::where('email', $email)->first();
    }

    public function createToken($email){
        $isToken = DB::table('password_resets')->where('email', $email)->first();
        if($isToken){
            return $isToken->token;
        }
        
        $generateToken = Str::random(80);;
        $this->saveToken($generateToken, $email);
        return $generateToken;
    }

    public function saveToken($generateToken, $email){
        DB::table('password_resets')->insert([
            'email'=>$email,
            'token'=>$generateToken,
            'create_at'=>Carbon::now()
        ]);
    }


}