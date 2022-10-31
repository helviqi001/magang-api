<?php

namespace App\Http\Controllers\CMS\Manage;

use App\Http\Controllers\Controller;
use App\Models\Kuliner;
use Illuminate\Http\Request;

class KulinerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kuliner = Kuliner::all();
        return response()->json(['message' => 'success','data' => $kuliner]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $kuliner = Kuliner::create($request->all());
        return response()->json(['message' => 'Data has been inserted success','data' => $kuliner]);
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
        return response()->json(['message' => 'success','data' => $kuliner]);
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
        $kuliner->update($request->all());
        return response()->json(['message' => 'Data has been updated success','data' => $kuliner]);
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
        $kuliner->delete();
        return response()->json(['message' => 'Data has been deleted success','data' => null]);
    }
}
