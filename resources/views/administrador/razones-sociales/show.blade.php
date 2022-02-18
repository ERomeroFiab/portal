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
                                <label for="direccion">Dirección</label>
                                <input disabled type="text" class="form-control" id="direccion" value="{{ $razon_social->direccion }}">
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
                                                    <th>Gestor</th>
                                                    <th>Inició</th>
                                                    <th>Finalizó</th>
                                                    <th>Fecha Depósito</th>
                                                    <th>Honorarios Fiabilis</th>
                                                    <th>Tipo</th>
                                                    <th>Motivo</th>
                                                    <th>Status</th>
                                                    <th>Reportes</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($razon_social->gestiones as $gestion)
                                                <tr>
                                                    <td>{{ $loop->iteration }} </td>
                                                    <td>{{ $gestion->gestor->name }} </td>
                                                    <td>{{ $gestion->fecha_inicio->format('d-m-Y') }} </td>
                                                    <td>{{ $gestion->fecha_cierre->format('d-m-Y') }} </td>
                                                    <td>{{ $gestion->fecha_deposito->format('d-m-Y') }} </td>
                                                    <td>{{ $gestion->honorarios_fiabilis }} </td>
                                                    <td>{{ $gestion->tipo }} </td>
                                                    <td>{{ $gestion->motivo }} </td>
                                                    <td>{{ $gestion->status }} </td>
                                                    <td class="text-center">{{ count($gestion->reportes) }} </td>
                                                    <td>
                                                        <a href="{{ route('admin.gestiones.show', ['id' => $gestion->id]) }}" class="btn btn-sm btn-info">Ver Detalle</a>
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