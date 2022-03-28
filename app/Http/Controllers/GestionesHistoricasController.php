<?php

namespace App\Http\Controllers;

use App\Models\GestionesHistoricas;
use App\Http\Requests\StoreGestionesHistoricasRequest;
use App\Http\Requests\UpdateGestionesHistoricasRequest;
use App\Models\Empresa;

class GestionesHistoricasController extends Controller
{
    public function cliente_index()
    {
        $empresa = Empresa::find( auth()->user()->empresa->id );

        return view('cliente.gestiones_historicas.index', [
            'razones_sociales' => $empresa->razones_sociales,
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
     * @param  \App\Http\Requests\StoreGestionesHistoricasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGestionesHistoricasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GestionesHistoricas  $gestionesHistoricas
     * @return \Illuminate\Http\Response
     */
    public function show(GestionesHistoricas $gestionesHistoricas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GestionesHistoricas  $gestionesHistoricas
     * @return \Illuminate\Http\Response
     */
    public function edit(GestionesHistoricas $gestionesHistoricas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGestionesHistoricasRequest  $request
     * @param  \App\Models\GestionesHistoricas  $gestionesHistoricas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGestionesHistoricasRequest $request, GestionesHistoricas $gestionesHistoricas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GestionesHistoricas  $gestionesHistoricas
     * @return \Illuminate\Http\Response
     */
    public function destroy(GestionesHistoricas $gestionesHistoricas)
    {
        //
    }
}
