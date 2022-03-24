@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h2>Razón Social <b>{{ $razon_social->nombre }}</b></h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form>

                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input disabled type="text" class="form-control" id="name" value="{{ $razon_social->nombre }}">
                            </div>

                            <div class="form-group">
                                <label for="rut">Rut</label>
                                <input disabled type="text" class="form-control" id="rut" value="{{ $razon_social->rut }}">
                            </div>

                            <div class="form-group">
                                <label for="contrato">Contrato</label>
                                <input disabled type="text" class="form-control" id="contrato" value="{{ $razon_social->contrato }}">
                            </div>

                            <div class="form-group">
                                <label for="no_entity">no_entity</label>
                                <input disabled type="text" class="form-control" id="no_entity" value="{{ $razon_social->no_entity }}">
                            </div>

                            <div class="form-group">
                                <label for="date_signature">date_signature</label>
                                <input disabled type="text" class="form-control" id="date_signature" value="{{ $razon_social->date_signature }}">
                            </div>

                            <div class="form-group">
                                <label for="suivi_par">suivi_par</label>
                                <input disabled type="text" class="form-control" id="suivi_par" value="{{ $razon_social->suivi_par }}">
                            </div>

                        </form>
                    </div>
                    
                </div>
            </div>
        </div> <!-- End card -->
    </div>
</div>
@endsection