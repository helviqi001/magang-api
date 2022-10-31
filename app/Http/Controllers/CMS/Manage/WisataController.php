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
