<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class WisataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wisata = Wisata::all();
        return response()->json(['message' => 'Success','data' => $wisata]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'gambar_wisata' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        //     'name_wisata' => ['required', 'max:255'],
        //     'deskripsi' => ['required', 'min:35],
        //     'harga' => ['required', 'max:255'],
        //     'fasilitas' => ['required', 'max:225'],
        //     'operasional' => ['required', 'min:4'],
        //     'lokasi' => ['required'],
        // ]);

        // if ($validator->fails()) {
        //     return $this->sendResponse(false, $validator->getMessageBag()->first())->setStatusCode(Response::HTTP_BAD_REQUEST);
        // }

        // $validator = Validator::ofSelect()->create($request->all());

        // return $this->sendResponse(true, 'Ok', $request);

        $wisata = Wisata::create($request->all());
        return response()->json(['message' => 'Data has been inserted success','data' => $wisata]);
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
        return response()->json(['message' => 'Success','data' => $wisata]);

        // $data = Wisata::ofSelect()->where('wisata_id', '=', $id)->first();

        // if ($data == null) {
        //     return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        // }

        // return $this->sendResponse(true, 'Ok', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wisata  $wisata
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $wisata = Wisata::find($id);
        $wisata->update($request->all());
        return response()->json(['message' => 'Data has been updated success','data' => $wisata]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wisata  $wisata
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wisata = Wisata::find($id);
        $wisata->delete();
        return response()->json(['message' => 'Data has been deleted success','data' => null]);
    }
}
