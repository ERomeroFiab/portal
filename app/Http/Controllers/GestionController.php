<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use App\Http\Requests\StoreGestionRequest;
use App\Http\Requests\UpdateGestionRequest;

class GestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gestiones = Gestion::all();
        
        return view('administrador.gestiones.index', [
            "gestiones" => $gestiones,
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
     * @param  \App\Http\Requests\StoreGestionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGestionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gestion  $gestion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gestion = Gestion::where('id', $id)->with('reportes')->first();
        
        return view('administrador.gestiones.show', [
            "gestion" => $gestion,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gestion  $gestion
     * @return \Illuminate\Http\Response
     */
    public function edit(Gestion $gestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGestionRequest  $request
     * @param  \App\Models\Gestion  $gestion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGestionRequest $request, Gestion $gestion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gestion  $gestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gestion $gestion)
    {
        //
    }
}
