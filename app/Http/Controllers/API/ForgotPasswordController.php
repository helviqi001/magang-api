<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\Customer;
use App\Models\PasswordReset;
use App\Notifications\OTPMail;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use PHPUnit\Framework\Constraint\Exception;


class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request){
        $request -> validate([
            'email'=> 'required','email',
        ]);
        $email = Customer::where('email', $request->email)->first();
        
        if (!empty($email)) {
            $otp = random_int(100000, 999999);
            $data = [
                'otp' => $otp,
            ];
            
            Customer::where('email', $request->email)->update($data);
            $data['email'] = Customer::where('email', $request->email)->first()->email;
            $data['subject'] = 'Your otp';
            $data['otp'] = $otp;
            $data['view'] = 'emails.forgotPassword';
            
        
            Mail::to($data['email'])->send(new ForgotPasswordMail($data));
            $row = Customer::where('email', $request->email)->first()->customer_id;
            
            return response()->json([
                'message'=> 'Check your email!',
                'data'=> $row
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                'message'=> 'The given data was invalid!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
  
    
    public function verifyOtp(Request $request){
        $request->validate([
            'otp'=> ['required'],
        ]);

        $customer_id = Customer::where('otp', $request->customer_otp)->first();

        if (!empty($customer_id)) {
            if(Auth::loginUsingId($customer_id->customer_id)){
                return response()->json([
                    'message'=> 'Logged in!'
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    'message'=> 'Wrong otp entered!'
                ], Response::HTTP_PAYMENT_REQUIRED);
            }
        }
    }


    public function resendOtp(Request $request, $email){
        $otp =random_int(100000, 999999);
        
        $data = [
            'otp'=> $otp,
        ];

        Customer::where('customer_id', $request->customer_id)->update($data);

        $data['email'] = Customer::where('customer_id', $request->customer_id)->first()->email;
        $data['subject'] = 'Your resended otp';
        $data['otp'] = $otp;

        Mail::to($data['email'])->send(new OTPMail($data));

        return response()->json([
            'message'=> 'Check resended otp in your email!'
        ], Response::HTTP_OK);
    }

    // public function reset(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'password'=> ['required'],
    //         'password_confirmation'=> ['required','same:password']
    //     ]);

    //     if($validator->fails()){
    //         return response()->json(['error'=>$validator->errors()], Response::HTTP_UNAUTHORIZED);
    //     }

    //     $reset_token = DB::table('password_resets')->where($input['token'])->first();
    //     if($reset_token){
    //         $customer = Customer::where('email', $reset_token->email)->first();
    //         DB::table('customers')->where('email', $reset_token->email)->update([
    //             "password"=> Hash::make($request->input('password'))
    //         ]);

    //         $token = $customer->createToken($customer->name)->accessToken;

    //         $success =[
    //             'name'=> $customer->name,
    //             'token'=> $token
    //         ];

    //         return response()->json([
    //             'message'=> 'User password reset successfully. Please login',
    //             'data'=> $success
    //         ], Response::HTTP_OK);
    //     }else{
    //         return response()->json([
    //             'message'=> 'invalid token'
    //         ], Response::HTTP_NOT_FOUND);
    //     }
    // }
}