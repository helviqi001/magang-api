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
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required', 'email',
        ]);
        $customer = Customer::where('email', $request->email)->first();

        if (!empty($customer)) {
            $otp = random_int(100000, 999999);
            $data = [
                'otp' => $otp,
            ];

            Customer::where('email', $request->email)->update($data);
            $data['email'] = Customer::where('email', $request->email)->first()->email;
            $data['body'] = 'Gunakan kode di bawah ini untuk mengatur ulang kata sandi anda.';
            $data['body2'] = 'Hello, ' . $customer->name . ' !';
            $data['subject'] = 'OTP Verification Forgot Password';
            $data['otp'] = $otp;


            Mail::send('emails.forgotPassword', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['subject']);
            });

            $row = Customer::where('email', $request->email)->first()->customer_id;

            return response()->json([
                'message' => 'Check your email!',
                'data' => $row
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'The given data was invalid!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    public function verifyOtp(Request $request)
    {
        $verifyotp = $request->otp;
        $verifyotp = Customer::where('otp', $verifyotp)->first();
        if ($verifyotp == true) {
            $verifyotp->otp_verify = 1;
            $verifyotp->save();
            $customer = Customer::where('customer_id', $verifyotp->customer_id)->first();
            $customer->otp_verify = 1;
            $customer->save();

            return response()->json([
                'message' => 'Verification Success'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Your OTP is invalid please check your email OTP first'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
            
        }
    }


    public function resendOtp(Request $request)
    {
        $customer = Customer::where('email', $request->email)->first();

        if (!empty($customer)) {
            $otp = random_int(100000, 999999);
            $data = [
                'otp' => $otp,
            ];

            Customer::where('email', $request->email)->update($data);
            $data['email'] = Customer::where('email', $request->email)->first()->email;
            $data['body'] = 'Gunakan kode di bawah ini untuk mengatur ulang kata sandi anda.';
            $data['body2'] = 'Hello back, ' . $customer->name . ' !';
            $data['subject'] = 'Resend OTP Verification';
            $data['otp'] = $otp;


            Mail::send('emails.forgotPassword', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['subject']);
            });

            $row = Customer::where('email', $request->email)->first()->customer_id;

            return response()->json([
                'message' => 'Check resended otp in your email!',
                'data' => $row
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'The given data was invalid!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function reset(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'password'=> ['required'],
        //     'password_confirmation'=> ['required','same:password']
        // ]);

        // if($validator->fails()){
        //     return response()->json(['error'=>$validator->errors()], Response::HTTP_UNAUTHORIZED);
        // }

        // $reset_token = DB::table('password_resets')->where($input['token'])->first();
        // if($reset_token){
        //     $customer = Customer::where('email', $reset_token->email)->first();
        //     DB::table('customers')->where('email', $reset_token->email)->update([
        //         "password"=> Hash::make($request->input('password'))
        //     ]);

        //     $token = $customer->createToken($customer->name)->accessToken;

        //     $success =[
        //         'name'=> $customer->name,
        //         'token'=> $token
        //     ];

        //     return response()->json([
        //         'message'=> 'User password reset successfully. Please login',
        //         'data'=> $success
        //     ], Response::HTTP_OK);
        // }else{
        //     return response()->json([
        //         'message'=> 'invalid token'
        //     ], Response::HTTP_NOT_FOUND);
        // }

            // $validator = Validator::make($request->all(), [
            //     'password'=> 'required|min:8|confirmed'
            // ]);
            // if($validator->fails()){
            //     return response()->json(['error'=>$validator->errors()], 401);
            // }
            
            // $customer = Customer::where('customer_id', $request->customer_id);
            // $customer->update(['password'=> bcrypt($request->password)]);
            // return response()->json([
            //     'success'=> true,
            //     'msg'=> 'password berhasil di update'
            // ], Response::HTTP_OK);
        
        $customer = PasswordReset::make($request->all(),[
            'password'=>'required|min:6|max:100',
            'confirm_password'=>'required|same:password'
        ]);
        if ($customer->fails()) {
            return response()->json([
                'message'=>'Validations fails',
                'errors'=>$customer->errors()
            ],422);
        }

        $customer_reset = PasswordReset::where('customer_id', $request->customer_id)->first();
        $customer_reset=$request->customer();
        if (Hash::check($request->id,$customer_reset->password)) {
            $customer_reset->update([
                'password'=>Hash::make($request->password)
            ]);
            return response()->json([
                'message'=>'Password successfully updated',
            ],200);
        }else {
            return response()->json([
                'message'=>'Customer does not matched',
            ],400);
        }
    }
}