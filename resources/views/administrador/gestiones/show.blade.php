@extends('layouts.app')

@section('content')

    @include('includes.messages_in_session')
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2>Gestión código: <b>{{ $gestion->id }}</b></h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form>

                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de Inicio</label>
                                    <input disabled type="text" class="form-control" id="fecha_inicio" value="{{ $gestion->fecha_inicio }}">
                                </div>

                                <div class="form-group">
                                    <label for="fecha_cierre">Fecha de Cierre</label>
                                    <input disabled type="text" class="form-control" id="fecha_cierre" value="{{ $gestion->fecha_cierre }}">
                                </div>


                            </form>
                        </div>
                        <div class="col-12 mt-3">
                            <h3>Reportes</h3>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">

                                            <table class="table-hover" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Titulo</th>
                                                        <th>Descripción</th>
                                                        <th>Fecha</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($gestion->reportes as $reporte)
                                                    <tr>
                                                        <td>{{ $loop->iteration }} </td>
                                                        <td>{{ $reporte->titulo }} </td>
                                                        <td>{{ $reporte->descripcion }} </td>
                                                        <td>{{ $reporte->created_at->format('d-m-Y H:i:s') }} </td>
                                                        <td>
                                                            <a href="{{ route('admin.reportes.show', ['id' => $reporte->id]) }}" class="btn btn-sm btn-info">Ver</a>
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