@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h2>Factura: <b>{{ $factura->id }}</b></h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form>

                            <div class="form-group">
                                <label for="fecha_inicio">Fecha de Inicio</label>
                                <input disabled type="text" class="form-control" id="fecha_inicio" value="{{ $factura->created_at->format('d-m-Y') }}">
                            </div>

                            <div class="form-group">
                                <label for="fecha_cierre">Fecha de Cierre</label>
                                <input disabled type="text" class="form-control" id="fecha_cierre" value="{{ $factura->fecha_cierre ? $factura->fecha_cierre : "Aún no se cierra" }}">
                            </div>


                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <h3>Gestiones</h3>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <table class="table-hover" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Razón Social</th>
                                                    <th>Glosa</th>
                                                    <th>Monto Gestionado</th>
                                                    <th>Monto Aprobado</th>
                                                    <th>Fee</th>
                                                    <th>Monto Facturado</th>
                                                    <th>Fecha de Inicio</th>
                                                    <th>Fecha de Cierre</th>
                                                    <th>Fecha de Depósito</th>
                                                    <th>Honorario Fiabilis</th>
                                                    <th>Tipo</th>
                                                    <th>Motivo</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($factura->gestiones as $gestion)
                                                <tr>
                                                    <td>{{ $loop->iteration }} </td>
                                                    <td title="Empresa: {{ $gestion->razon_social->empresa->nombre }}">
                                                        {{ $gestion->razon_social->nombre }} ({{ $gestion->razon_social->rut }}) 
                                                    </td>
                                                    <td>{{ $gestion->glosa }} </td>
                                                    <td>{{ $gestion->monto_gestionado }} </td>
                                                    <td>{{ $gestion->monto_aprobado }} </td>
                                                    <td>{{ $gestion->fee }}% </td>
                                                    <td>{{ $gestion->monto_factura }} </td>
                                                    <td>{{ $gestion->fecha_inicio->format('d-m-Y') }} </td>
                                                    <td>{{ $gestion->fecha_cierre->format('d-m-Y') }} </td>
                                                    <td>{{ $gestion->fecha_deposito->format('d-m-Y') }} </td>
                                                    <td>{{ $gestion->honorarios_fiabilis }} </td>
                                                    <td>{{ $gestion->tipo }} </td>
                                                    <td>{{ $gestion->motivo }} </td>
                                                    <td>
                                                        <a href="{{ route('cliente.gestiones.show', ['id' => $gestion->id]) }}" class="btn btn-sm btn-info">Ver</a>
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