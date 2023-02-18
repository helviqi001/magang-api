<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($success, $message, $data = null)
    {
        $response['success'] = $success;
        $response['message'] = $message;
        $response['data'] = $data;

        return response()->json($response);
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
