@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
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
                                                    <th>Empresa</th>
                                                    <th>Razón Social</th>
                                                    <th>Rut</th>
                                                    <th>Inició</th>
                                                    <th>Finalizó</th>
                                                    <th>Tipo</th>
                                                    <th>Motivo</th>
                                                    <th>Status</th>
                                                    <th>Reportes</th>
                                                    <th class="no_exportar">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($gestiones as $gestion)
                                                <tr>
                                                    <td>{{ $loop->iteration }} </td>
                                                    <td>{{ $gestion->razon_social->empresa->nombre }} </td>
                                                    <td>{{ $gestion->razon_social->nombre }} </td>
                                                    <td>{{ $gestion->razon_social->rut }} </td>
                                                    <td>{{ $gestion->fecha_inicio->format('d-m-Y') }} </td>
                                                    <td>{{ $gestion->fecha_cierre->format('d-m-Y') }} </td>
                                                    <td>{{ $gestion->tipo }} </td>
                                                    <td>{{ $gestion->motivo }} </td>
                                                    <td>{{ $gestion->status }} </td>
                                                    <td class="text-center">{{ count($gestion->reportes) }} </td>
                                                    <td>
                                                        <a href="{{ route('consultor.gestiones.show', ['id' => $gestion->id]) }}" class="btn btn-sm btn-info">Ver</a>
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