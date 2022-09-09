<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use App\Helper\CaseStylesHelper;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CredentialController extends Controller
{
    use CaseStylesHelper;

    public function AuthSystem(Request $request)
    {
        $request->request->replace($this->convertCaseStyle('snakeCase', $request->only(['clientKey', 'secretKey'])));
        $validator = Validator::make($request->all(), [
            'client_key' => 'required',
            'secret_key' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, $validator->getMessageBag()->first());
        }
        $credential = Credential::where('client_key', $request->get('client_key'))->first();
        if ($credential == null && !Hash::check($request->get('secret_key'), $credential->secret_key)) {
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

    public function jwt($credential)
    {
        $payload = [
            'iss' => \URL::to('/'),
            'iat' => time(),
            'exp' => 0,
            'platform' => $credential->platform,
            'scope' => env('APP_ENV'),
            'type' => $credential->type,
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
}
