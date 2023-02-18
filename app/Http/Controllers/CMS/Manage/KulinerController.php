<?php

namespace App\Http\Controllers\CMS\Manage;

use App\Http\Controllers\Controller;
use App\Models\Kuliner;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class KulinerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * menampilkan semua list kuliner
     */
    public function index(Request $request)
    {
        $query = Kuliner::query();

        if ($request->has('keyword')) {
            $query->whereRaw("name_kuliner LIKE '%" . $request->get('keyword') . "%'");
        }

        if ($request->has('order_by')) {
            $query->orderBy($request->get('order_by'), $request->get('order'));
        }
        $query = $query->paginate((int)$request->get('limit') ?? 10);

        $result = [
            'items'=> $query->items(),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $kuliner = Kuliner::create($request->all());
        // return response()->json(['message' => 'Data has been inserted success','data' => $kuliner]);

        // if($kuliner->fails()){
        //     return response()->json(['error'=>$kuliner->errors()], 406);
        // }

        // return $this->sendResponse(true, 'Ok', $kuliner);

        $data = $request->all();
        $validator = Validator::make($data, [
            'gambar_kuliner'=> 'required',
            'name_kuliner'=> 'required',
            'deskripsi'=> 'required',
            'harga_reguler'=> 'required',
            'harga_jumbo'=> 'required',
            'operasional'=> 'required',
            'lokasi'=> 'required',
            'latitude'=> 'required',
            'longitude'=> 'required'
        ]);

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $wisata = Kuliner::create($data);
        return response()->json([
            'success'=> true,
            'data'=> $wisata
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kuliner  $kuliner
     * @return \Illuminate\Http\Response
     * menampilkan kuliner berdasarkan id
     */
    public function show($id)
    {
        $kuliner = Kuliner::find($id);
        // return response()->json(['message' => 'success','data' => $kuliner]);

        if ($kuliner->fails()) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(true, 'Ok', $kuliner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kuliner  $kuliner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $kuliner = Kuliner::find($id);
        // $kuliner->update($request->all());
        // return response()->json(['message' => 'Data has been updated success','data' => $kuliner]);

        if ($kuliner->fails()) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $kuliner->update($request->all());

        return $this->sendResponse(true, 'Ok', $kuliner);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kuliner  $kuliner
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kuliner = Kuliner::find($id);
        if ($kuliner->fails()) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        $kuliner->delete();
        return response()->json(['message' => 'Data has been deleted success','data' => null]);
    }
}
