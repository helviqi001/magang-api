<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset as ResetPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;


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
        //     'password'=> 'required|min:8',
        //     'password_confirmation'=> 'required|same:password',
        // ]);
        
        // if($validator->fails()){
        //     return response()->json(['error'=>$validator->errors()], Response::HTTP_UNAUTHORIZED);
        // }

        // $customer = Customer::where('email', $request->email)->first();
        // if($customer){
        //     $customer->password = bcrypt($request->password);
        //     $customer->save();
        //     return response()->json([
        //         'success'=> true,
        //         'data'=> $customer,            
        //     ], Response::HTTP_OK);
        // }else{
        //     return response()->json(['success'=>false]);
        // }

        
    }
}