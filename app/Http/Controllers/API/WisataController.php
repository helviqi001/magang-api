<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WisataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $query = Wisata::query();

        if ($s = $request->input('s')) {
            $query->whereRaw("name_wisata LIKE '%" . $s . "%'");
        }

        if ($sort = $request->input('sort')) {
            $query->ordeyBy('name_wisata', $sort);
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
     * @param  \App\Models\Wisata  $wisata
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $wisata = Wisata::find($id);

        if ($wisata == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return response()->json(['message' => 'Success','data' => $wisata]);
    }
}
