@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h2>Empresa <b>{{ $empresa->nombre }}</b></h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form>

                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input disabled type="text" class="form-control" id="name" value="{{ $empresa->nombre }}">
                            </div>

                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <input disabled type="text" class="form-control" id="tipo" value="{{ $empresa->tipo }}">
                            </div>


                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <h3> {{ count($empresa->razones_sociales) < 2 ? count($empresa->razones_sociales)." Razón Social" : count($empresa->razones_sociales)." Razones Sociales" }}</h3>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <table class="table-hover" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Nombre</th>
                                                    <th>Rut</th>
                                                    <th>Gestiones Finalizadas</th>
                                                    <th>Gestiones Pendientes</th>
                                                    <th class="no_eportar">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($empresa->razones_sociales as $razon_social)
                                                <tr>
                                                    <td>{{ $loop->iteration }} </td>
                                                    <td>{{ $razon_social->nombre }} </td>
                                                    <td>{{ $razon_social->rut }} </td>
                                                    <td>{{ count($razon_social->gestiones_finalizadas) }} </td>
                                                    <td>{{ count($razon_social->gestiones_pendientes) }} </td>
                                                    <td>
                                                        <a href="{{ route('consultor.razones-sociales.show', ['id' => $razon_social->id]) }}" class="btn btn-sm btn-info">Ver</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End card -->
    </div>
</div>
@endsection