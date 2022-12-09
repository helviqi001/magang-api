<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Password;
use App\Models\Credential;
use Firebase\JWT\JWT;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', Rule::unique('customers')->whereNull('deleted_at')],
            'no_telp' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        } 

        $customer=Customer::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'no_telp'=>$request->no_telp,
            'password'=>Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Data berhasil ditambahkan',
            'data' => $customer
        ]);
    }


 
    public function login(Request $request)
    {
        
        $customer = Customer::where('email', $request->email);

        if ($customer->exists()) {
            $customer = $customer->first();
            if (Hash::check($request->get('password'), $customer->password)) {
                $payload = $this->jwt($request->token, $customer);
                $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

                return $this->sendResponse(true, 'Ok', [
                    'customerAuth' => [
                        'customer_id' => $customer->customer_id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'no_telp' => $customer->no_telp,
                    ],
                    'platform' => $request->token->platform,
                    'scope' => $payload['scope'],
                    'type' => $request->token->type ?? '',
                    'issuedAt' => $payload['iat'],
                    'expiredAt' => $payload['exp'],
                    'token' => $token,
                ])->setStatusCode(Response::HTTP_OK);
            }
        }

        return $this->sendResponse(false, "These credentials do not match our records")
            ->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function jwt($token, $customer)
    {
        $payload = [
            'iss' => \URL::to('/'),
            'iat' => time(),
            'sub' => $customer->customer_id,
            'exp' => 0,
            'platform' => $token->platform,
            'scope' => env('APP_ENV'),
            'type' => $token->type,
        ];

        switch ($payload['platform']) {
            case 'Web':
                $payload['exp'] = time() + 60 * 60 * 24 * env('TOKEN_WEB_EXPIRE', 30);
                break;
            case 'Backoffice':
                $payload['exp'] = time() + 60 * 60 * 24 * env('TOKEN_BACKOFFICE_EXPIRE', 30);
                break;
            case 'Android':
                $payload['exp'] = time() + 60 * 60 * 24 * env('TOKEN_ANDROID_EXPIRE', 30);
                break;
            case 'IOS':
                $payload['exp'] = time() + 60 * 60 * 24 * env('TOKEN_IOS_EXPIRE', 30);
                break;
        }

        return $payload;
    }


   
    public function logout()
    {
        // auth()->logout();
        // return ['message'=>'Berhasil logout!'];
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK);
    }


<<<<<<< HEAD
    // public function reset()
    // {
    //     $credentials = request()->validate([
    //         'email' => 'required|email',
    //         'token' => 'required|string',
    //         'password' => 'required|string|confirmed'
    //     ]);
=======
    public function profile(Request $id)
    {
        $customer = Customer::find($id);
        return response()->json(['message' => 'success', 'data' => $customer]);
    }

    public function profedit(Request $request,$id)
    {
        $customer = Customer::where('customer_id', $id)->first();
        if ($customer) {
            $customer->update($request->all());
            return response()->json([
                'message' => "Success",
                'data' => $customer
            ],200);
        }

        return response()->json([
            'message' => "Tidak ada Customer!"
        ], 404);
    }
>>>>>>> d4da049baa86dc4dc71e8c5fbb2fd2f35a1ad675

    //     $reset_password_status = Password::reset($credentials, function ($customer, $password) {
    //         $customer->password = Hash::make($password);
    //         $customer->save();
    //     });

    //     if ($reset_password_status == Password::INVALID_TOKEN) {
    //         return response()->json(["msg" => "Invalid token provided"], 400);
    //     }

    //     return response()->json(["msg" => "Password has been successfully changed"]);
    // }
}