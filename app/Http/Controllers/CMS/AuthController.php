<?php

namespace App\Http\Controllers\CMS;

use App\Helper\CaseStylesHelper;
use App\Http\Controllers\Controller;
use App\Models\MenuGroup;
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
        $user = User::where($this->username(), $request[$this->username()]);

        if ($user->exists()) {
            $user = $user->first();
            if (Hash::check($request->get('password'), $user->password)) {
                $payload = $this->jwt($request->token, $user);
                $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

                return $this->sendResponse(true, 'Ok', [
                    'userAuth' => [
                        'userId' => $user->user_id,
                        'name' => $user->name,
                        'avatar' => $user->avatar ?? (\URL::to('/') . '/file-upload/Avatar/default.png'),
                        'role' => $user->role
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

    public function myPrivileges(Request $request)
    {
        $user = User::where('user_id', $request->auth->sub)->with('role.privileges')->first();

        $menuIds = collect($user->role->privileges)->map(function ($data) {
            return $data->menu_item_id;
        });


        $menuGroup = MenuGroup::select(['menu_group_id', 'name', 'sequence', 'icon', 'status'])
            ->whereHas('Menus')
            ->orderBy('sequence', 'asc')
            ->with(['Menus' => function ($query) use ($menuIds) {
                $query->select(['menu_item_id', 'name', 'url', 'menu_group_id', 'sequence'])
                    ->orderBy('sequence', 'asc')
                    ->whereIn('menu_item_id', $menuIds);
            }])->has('Menus')
            ->get()->toArray();


        $menuGroup = collect($menuGroup)->filter(function ($data) {
            return count($data['menus']) > 0;
        });

        return $this->sendResponse(true, 'Ok', $menuGroup);
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


