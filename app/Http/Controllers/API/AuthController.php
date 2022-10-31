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
use App\Models\Credential;
use Firebase\JWT\JWT;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', Rule::unique('customers')->whereNull('deleted_at')],
            'no_telp' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $customer=Customer::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'no_telp'=>$request->no_telp,
            'password'=>Hash::make($request->password)
        ]);

        return response()->json([
            'data' => $customer,
            'message' => 'Data berhasil ditambahkan'
        ]);
    }


    //Controller Login
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


    //Controller logout
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


    public function profile(Request $request)
    {
        return response()->jon([
            'data' => $request->customer
        ], Response::HTTP_OK);
    }

    public function forgot()
    {
        $credentials = request()->validate(['email' => 'required|email']);
        Password::sendResetLink($credentials);

        return response()->json([
            'message' => 'Reset password link sent on your email id'
        ]);
    }

    public function reset()
    {
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