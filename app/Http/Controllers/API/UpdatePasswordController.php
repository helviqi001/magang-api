<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\RequestHelper;
use App\Models\Customer;
use Dotenv\Validator;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;


class UpdatePasswordController extends Controller
{
    // public function updatePassword(RequestHelper $request){
    //     return $this->validateToken($request)->count() > 0 ? $this->changePassword($request) : $this->noToken();
    // }

    // private function validateToken($request){
    //     return DB::table('password_resets')->where([
    //         'email' => $request->email,
    //         'token' => $request->passwordToken
    //     ]);
    // }

    // private function noToken() {
    //     return response()->json([
    //       'error' => 'Email or token does not exist.'
    //     ],Response::HTTP_UNPROCESSABLE_ENTITY);
    // }

    // private function changePassword($request) {
    //     $customer = Customer::whereEmail($request->email)->first();
    //     $customer->update([
    //       'password'=>bcrypt($request->password)
    //     ]);
    //     $this->validateToken($request)->delete();
    //     return response()->json([
    //       'data' => 'Password changed successfully.'
    //     ],Response::HTTP_CREATED);
    // }  

    public function updatePassword(Request $request){
      $validator = \Validator::make($request->all(),[
        'password'=> 'required',
        'password_confirmation'=> 'required|same:password', 
      ]);

      if($validator->fails()){
        return $this->errorBadRequest($validator);
      }

      $customer= $this->customer();
      $auth= \Auth::once([
        'email'=> $customer->email,
        'password'=> $request->get('password'),
      ]);

      if(!$auth){
        return $this->response->errorUnauthotized();
      }

      $password = app('hash')->make($request->get('password'));
      $customer->update(['password'=> $request]);

      return $this->response->noContent();
    }
}