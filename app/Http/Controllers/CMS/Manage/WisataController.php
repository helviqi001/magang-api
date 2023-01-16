<?php

namespace App\Http\Controllers\CMS\Manage;

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
        // return response()->json(['message' => 'Success','data' => $wisata]);
        
        if ($wisata->fails()) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(true, 'Ok', $wisata);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $wisata = Wisata::create($request->all());
        // return response()->json(['message' => 'Data has been inserted success','data' => $wisata]);

        // if ($wisata == null) {
        //     return $this->sendResponse(false, $wisata->getMessageBag()->first())->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
        // }

        if($wisata->fails()){
            return response()->json(['error'=>$wisata->errors()], 406);
        }

        return $this->sendResponse(true, 'Ok', $wisata);
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
        // return response()->json(['message' => 'Success','data' => $wisata]);

        // $data = Wisata::ofSelect()->where('wisata_id', '=', $id)->first();

        if ($wisata->fails()) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse(true, 'Ok', $wisata);

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
