<?php

namespace App\Http\Controllers\CMS;

use App\Helper\CaseStylesHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    use CaseStylesHelper;
    public function username()
    {
        return 'email';
    }

    public function login(Request $request)
    {
        $user = User::ofSelect()->where($this->username(), $request[$this->username()])->first();

        $payload = $this->jwt($request->token, $user);
        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        if (empty($user) && !Hash::check($request->password, $user->password)) {
            return $this->sendResponse(false, "These credentials do not match our records")->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $this->sendResponse(true,'Ok', [
            'userAuth' => [
                'userId' => $user->user_id,
                'name' => $user->name,
                'avatar' => $user->avatar,
            ],
            'platform' => $request->token->platform,
            'scope' => $payload['scope'],
            'type' => $request->token->type ?? '',
            'issuedAt' => $payload['iat'],
            'expiredAt' => $payload['exp'],
            'token' => $token,
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function jwt($token, $user)
    {
        $payload = [
            'iss' => \URL::to('/'),
            'iat' => time(),
            'sub' => $user->user_id,
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
}


