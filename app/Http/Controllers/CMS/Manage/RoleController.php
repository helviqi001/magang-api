<?php

namespace App\Http\Controllers\CMS\Manage;

use App\Http\Controllers\Controller;
use App\Models\Privilege;
use App\Models\Role;
use App\Helper\CaseStylesHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
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

        $data = Role::ofSelect()
            ->filter($request->all())
            ->orderBy($sortBy, $sort);
        $data = $data->paginate((int)$request->limit ?? 10);

        $result = [
            'currentPage' => $data->currentPage(),
            'from' => $data->firstItem() ?? 0,
            'lastPage' => $data->lastPage(),
            'perPage' => $data->perPage(),
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
            'privileges' => ['required', 'array'],
            'privileges.*.menu_item_id' => ['required', 'exists:menu_items,menu_item_id']
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, $validator->getMessageBag()->first())->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $dataRole = Role::ofSelect()->create($request->only('name'));
        $dataRole->privileges()->createMany($request->get('privileges'));

        return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $dataRole));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $data = Role::ofSelect()->with([
            'privileges' => function ($tagQuery) {
                $tagQuery->ofSelect();
            }
        ])->where('role_id', '=', $id)->first();

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
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, $validator->getMessageBag()->first())->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = Role::ofSelect()->where('role_id', '=', $id)->first();

        if ($data == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        $data->update($request->only('name', 'description'));

        foreach ($request->get('privileges') as $privilege) {
            Privilege::where('privilege_id', '=', $privilege['privilege_id'])->update($privilege);
        }

        return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $data = Role::ofSelect()->where('role_id', '=', $id)->first();
        if ($data == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $data->privileges()->delete();
        $data->delete();
        return $this->sendResponse(true, 'Ok', $this->convertCaseStyle('camelCase', $data));
    }
}
