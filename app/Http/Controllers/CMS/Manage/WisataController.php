<?php

namespace App\Http\Controllers\CMS\Manage;

use App\Http\Controllers\Controller;
use App\Models\Customer;
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
     *  Menampilkan tampilan Utama
     */
    public function index(Request $request)
    {
        // $wisata = Wisata::latest()->get();
        // return response()->json(['message' => 'Success','data' => $wisata]);
        
        // if ($wisata==null) {
        //     return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        // }

        // return $this->sendResponse(true, 'Ok', $wisata);

        $data['q'] = $request->q;
        $data['rows'] = Wisata::where('name_wisata', 'like', '%' . $request->q . '%')->get();
        return response()->json([$data], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $wisata = Wisata::create($request->all());
        // // return response()->json(['message' => 'Data has been inserted success','data' => $wisata]);

        // // if ($wisata == null) {
        // //     return $this->sendResponse(false, $wisata->getMessageBag()->first())->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
        // // }

        // return $this->sendResponse(true, 'Ok', $wisata);

        // if($wisata==null){
        //     return response()->json(['error'=>$wisata->errors()], 406);
        // }

        $data = $request->all();
        $validator = Validator::make($data, [
            'gambar_wisata'=> 'required',
            'name_wisata'=> 'required',
            'deskripsi'=> 'required',
            'harga_dewasa'=> 'required',
            'harga_anak'=> 'required',
            'fasilitas'=> 'required',
            'operasional'=> 'required',
            'lokasi'=> 'required',
            'latitude'=> 'required',
            'longitude'=> 'required'
        ]);

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }

        $wisata = Wisata::create($data);
        return response()->json([
            'success'=> true,
            'data'=> $wisata
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wisata  $wisata
     * @return \Illuminate\Http\Response
     * menampilkan wisata berdasarkan id
     */
    public function show($id)
    {
        // $wisata = Wisata::find($id);
        // // return response()->json(['message' => 'Success','data' => $wisata]);

        // // $data = Wisata::ofSelect()->where('wisata_id', '=', $id)->first();

        // if ($wisata->fails()) {
        //     return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        // }

        // return $this->sendResponse(true, 'Ok', $wisata);
        

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
        // return response()->json(['message' => 'Data has been updated success','data' => $wisata]);

        if ($wisata->fails()) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $wisata->update($request->all());

        return $this->sendResponse(true, 'Ok', $wisata);
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
        if ($wisata->fails()) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        $wisata->delete();
        return response()->json(['message' => 'Data has been deleted success','data' => null]);
    }
}
