<?php

namespace App\Http\Controllers;

use App\Models\RazonSocial;
use App\Http\Requests\StoreRazonSocialRequest;
use App\Http\Requests\UpdateRazonSocialRequest;

class RazonSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $razon_social = RazonSocial::all();
        
        return view('administrador.razones-sociales.index', [
            "razon_social" => $razon_social,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRazonSocialRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRazonSocialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $razon_social = RazonSocial::where('id', $id)->with('gestiones')->first();
        
        return view('administrador.razones-sociales.show', [
            "razon_social" => $razon_social,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function edit(RazonSocial $razonSocial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRazonSocialRequest  $request
     * @param  \App\Models\RazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRazonSocialRequest $request, RazonSocial $razonSocial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RazonSocial  $razonSocial
     * @return \Illuminate\Http\Response
     */
    public function destroy(RazonSocial $razonSocial)
    {
        //
    }
}
