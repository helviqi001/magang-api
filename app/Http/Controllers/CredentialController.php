<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CredentialController extends Controller
{
    public function AuthSystem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_key' => 'required',
            'secret_key' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, $validator->getMessageBag()->first());
        }
        $credential = Credential::where('client_key', $request->get('client_key'))->first();
        if ($credential == null || !Hash::check($request->get('secret_key'), $credential->secret_key)) {
            return $this->sendResponse(false, "These credentials do not match our records")->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $payload = $this->jwt($credential);
        $token = JWT::encode($payload, env('JWT_SECRET') . 'token', 'HS256');

        return $this->sendResponse(true, 'Token generated', [
            'platform' => $credential->platform,
            'scope' => env('APP_ENV'),
            'type' => $credential->type ?? '',
            'issuedAt' => $payload['iat'],
            'expiredAt' => $payload['exp'],
            'token' => $token,
        ]);
    }
}
