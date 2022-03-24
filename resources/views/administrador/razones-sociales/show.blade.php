@extends('layouts.app')

@section('content')
    @include('includes.messages_in_session')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Razón Social <b>{{ $razon_social->nombre }}</b></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input disabled type="text" class="form-control" id="name" value="{{ $razon_social->nombre }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="rut">Rut</label>
                                <input disabled type="text" class="form-control" id="rut" value="{{ $razon_social->rut }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="contrato">Ciudad</label>
                                <input disabled type="text" class="form-control" id="contrato" value="{{ $razon_social->ciudad }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="date_signature">Codigo Postal</label>
                                <input disabled type="text" class="form-control" id="date_signature" value="{{ $razon_social->codigo_postal }}">
                            </div>
                        </div>

                        
                    </div>

                    <div class="row mt-5">

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <pre>
                                        @dd($razon_social)
                                    </pre>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table>
                                        tabla missions_motives
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table>
                                        tabla missions_motive_eco
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection