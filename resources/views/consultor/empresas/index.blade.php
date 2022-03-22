@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h2>{{ count($empresas) < 2 ? count($empresas)." Empresa" : count($empresas)." Empresas" }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Representante</th>
                                    <th>Email</th>
                                    <th>Razones Sociales</th>
                                    <th class="no_eportar">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empresas as $empresa)
                                <tr>
                                    <td>{{ $loop->iteration }} </td>
                                    <td>{{ $empresa->nombre }} </td>
                                    <td>{{ $empresa->tipo }} </td>
                                    <td>
                                        @if ($empresa->representante)
                                            <a target="_blank" href="{{ route('consultor.usuarios.show', ['id' => $empresa->representante->id]) }}">
                                                {{$empresa->representante->name}}
                                            </a>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($empresa->representante)
                                            {{$empresa->representante->email}}
                                        @else
                                            <span>-</span>
                                        @endif 
                                    </td>
                                    <td>
                                        {{ count( $empresa->razones_sociales ) }}
                                    </td>
                                    <td>
                                        <a href="{{ route('consultor.empresas.show', ['id' => $empresa->id]) }}" class="btn btn-sm btn-info">Ver</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- End card -->
    </div>
</div>
@endsection