@extends('layouts.app')


@section('customcss')
    <style>
        @font-face {
    font-family: LeanOSans_FY_Regular;
    src: url('/fonts/LeanOSans_FY_Regular.eot');
}   
        
        #tabla_empresas_filter {
            display: none;
        }
        
    </style>
@endsection

@section('content')
<link rel="stylesheet" href="{{ URL::asset('css/bt.css') }}" />
    @include('includes.messages_in_session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Empresas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3 form-group">
                            <label>Nombre:</label>
                            <input id="input__Nombre" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Usuario:</label>
                            <input id="input__cliente" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Razones Sociales:</label>
                            <input id="input__razones_sociales_count" type="text" class="form-control">
                        </div>
                        <div class="col-3 form-group">
                            <label>Gestiones:</label>
                            <input id="input__gestiones_count" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class=" btn btn-sm btn-success float-right" type="button" onclick="buscar()">Buscar</button>
                        </div>
                    </div>
                        <div class="col-12">
                            <table id="tabla_empresas" class="table-hover" style="width:100%;">
                                <thead class="table-header-fiabilis">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Razones Sociales</th>
                                        <th>Gestiones</th>
                                        <th class="no_exportar">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- SERVER SIDE RENDERING --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- End card -->
        </div>
    </div>

    

@endsection

