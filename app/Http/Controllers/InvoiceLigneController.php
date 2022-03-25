<?php

namespace App\Http\Controllers;

use App\Models\InvoiceLigne;
use App\Http\Requests\StoreInvoiceLigneRequest;
use App\Http\Requests\UpdateInvoiceLigneRequest;

class InvoiceLigneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreInvoiceLigneRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceLigneRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceLigne  $invoiceLigne
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceLigne $invoiceLigne)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceLigne  $invoiceLigne
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceLigne $invoiceLigne)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInvoiceLigneRequest  $request
     * @param  \App\Models\InvoiceLigne  $invoiceLigne
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceLigneRequest $request, InvoiceLigne $invoiceLigne)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceLigne  $invoiceLigne
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceLigne $invoiceLigne)
    {
        //
    }
}
