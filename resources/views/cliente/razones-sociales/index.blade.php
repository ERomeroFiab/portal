@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mt-3">
                        <h3>Razones Sociales</h3>
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
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($empresa->razones_sociales as $razon_social)
                                                <tr>
                                                    <td>{{ $loop->iteration }} </td>
                                                    <td title="Empresa: {{ $razon_social->empresa->nombre }}">
                                                        {{ $razon_social->nombre }} 
                                                    </td>
                                                    <td>{{ $razon_social->rut }} </td>
                                                    <td>{{ count($razon_social->gestiones_finalizadas) }} </td>
                                                    <td>{{ count($razon_social->gestiones_pendientes) }} </td>
                                                    <td>
                                                        <a href="{{ route('cliente.razones-sociales.show', ['id' => $razon_social->id]) }}" class="btn btn-sm btn-info">Ver</a>
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