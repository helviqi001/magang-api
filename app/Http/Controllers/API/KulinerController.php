<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kuliner;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KulinerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Kuliner::query();

        if ($s = $request->input('s')) {
            $query->whereRaw("name_kuliner LIKE '%" . $s . "%'");
        }

        if ($sort = $request->input('sort')) {
            $query->ordeyBy('name_kuliner', $sort);
        }
        $query = $query->paginate((int)$request->limit ?? 10);

        $result = [
            'data'=> $query,
            'currentPage' => $query->currentPage(),
            'from' => $query->firstItem() ?? 0,
            'lastPage' => $query->lastPage(),
            'perPage' => $query->perPage(),
            'to' => $query->lastItem() ?? 0,
            'total' => $query->total()
        ];

        return $this->sendResponse(true, 'Ok', $result);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kuliner  $kuliner
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kuliner = Kuliner::find($id);

        if ($kuliner == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->sendResponse(true, 'Ok', $kuliner);
    }
}
