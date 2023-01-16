<?php

namespace App\Http\Controllers\CMS\Manage;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helper\CaseStylesHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use CaseStylesHelper;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $request->request->replace($this->convertCaseStyle('snakeCase', $request->all()));

        $sortBy = $request->input('sort_by') ?? 'created_at';
        $sort = $request->input('sort') ?? 'desc';

        $data = User::ofSelect()
            ->filter($request->all())
            ->orderBy($sortBy, $sort);

        $data = $data->paginate($request->limit ?? 10);
        foreach ($data->items() as $item) {
            $item->avatar = $item->avatar ?? \URL::to('/') . '/file-upload/Avatar/default.png';
        }

        $result = [
            'currentPage' => $data->currentPage(),
            'from' => $data->firstItem() ?? 0,
            'lastPage' => $data->lastPage(),
            'perPage' => (int)$data->perPage(),
            'to' => $data->lastItem() ?? 0,
            'total' => $data->total(),
            'items' => $this->convertCaseStyle('camelCase', $data->items())
        ];

        return $this->sendResponse(true, 'Ok', $result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->request->replace($this->convertCaseStyle('snakeCase', $request->all()));
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'username' => ['required', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'avatar' => ['required', 'max:255'],
            'birth_date' => ['required', 'date'],
            'password' => ['required', 'min:4']
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, $validator->getMessageBag()->first())->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $request->request->set('password', Hash::make($request->get('password')));
        $data = User::ofSelect()->create($request->all());

        return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $data));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $data = User::ofSelect()->where('user_id', '=', $id)->first();

        if ($data == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $data));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->request->replace($this->convertCaseStyle('snakeCase', $request->all()));
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',user_id,deleted_at,null',
            'username' => 'required|max:255|unique:users,email,' . $id . ',user_id,deleted_at,null',
            'avatar' => ['max:255'],
            'birth_date' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, $validator->getMessageBag()->first())->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = User::ofSelect()->where('user_id', '=', $id)->first();

        if ($data == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        if ($request->get('avatar') == null && $request->get('password') == null) {
            $param = $request->except('avatar', 'password');
            $update = User::where('user_id', $id)->update($param);

            if ($update) {
                return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $request->all()));
            }
        } elseif ($request->get('avatar') == null) {
            $param = $request->except('avatar');
            $update = User::where('user_id', $id)->update($param);

            if ($update) {
                return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $request->all()));
            }
        }  elseif ($request->get('password') == null) {
            $param = $request->except('password');
            $update = User::where('user_id', $id)->update($param);

            if ($update) {
                return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $request->all()));
            }
        }

        return $this->sendResponse(false, 'Error', (object) array());

        // if ($request->get('password') != "") {
        //     $request->request->set('password', Hash::make($request->get('password')));
        // }

        // $data->fill($request->all())->save();
        // return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $data = User::ofSelect()->where('user_id', '=', $id)->first();
        if ($data == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $data->delete();
        return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $data));
    }
}
