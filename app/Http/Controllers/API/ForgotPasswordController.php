<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


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

    public function reset($customer_id, Request $request)
    {   

        $validator = Validator::make($request->all(),[
            'password' =>'required',
            'confirm_password' =>'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, "Validation Error.")->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $reset = DB::table('customers')->where('customer_id', $customer_id)->first();
        if ($reset) {
            $customer = Customer::where('password', $reset->password)->first();
            DB::table('customers')->where('password', $reset->password)->update([
                "password" => Hash::make($request->input('password'))
            ]);

            $customer->save();
            $customer_id = $customer->find($customer->customer_id)->accessId;

            $succes = [
                "email" => $customer->email,
                "customer_id" => $customer->customer_id
            ];

            return $this->sendResponse(true, 'Customer Password Reset successfully. Please login', $succes);

        }else{
            return $this->sendResponse(false, "Invalid ID", [] )->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

    }
}