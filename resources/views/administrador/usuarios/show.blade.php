@extends('layouts.app')

@section('content')

    @include('includes.messages_in_session')


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Usuario <b>{{ $user->name }}</b></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form>

                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input disabled type="text" class="form-control" id="name" value="{{ $user->name }}">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input disabled type="text" class="form-control" id="email"
                                        value="{{ $user->email }}">
                                </div>

                                <div class="form-group">
                                    <label for="rut">Rut</label>
                                    <input disabled type="text" class="form-control" id="rut"
                                        value="{{ $user->rut }}">
                                </div>

                                <div class="form-group">
                                    <label for="rolInput">Rol</label>
                                    <input disabled type="text" class="form-control" id="rolInput"
                                        value="{{ $user->rol }}">
                                </div>
                                
                                @if ( $user->empresa )
                                    <div id="empresaInput" class="form-group">
                                        <label for="rolInput">Empresa</label>
                                        <input disabled type="text" class="form-control" id="rolInput" value="{{ $user->empresa->nombre }}">
                                    </div>
                                @endif


                            </form>
                        </div>
                    </div>

                    @if ( $user->empresa ) 

                        <div class="row mt-3">
                            <div class="col-12">
                                <a class="btn btn-sm btn-success" href="{{ route('silver.get_razones_sociales_from_silvertool_by_group_name', ['empresa_id' => $user->empresa->id]) }}" onclick="$(this).prop('disabled', true).text('Actualizando...');">
                                    Traer las razones sociales de Silvertool
                                </a>
                            </div>
                            <div class="col-12">
                                <small>(Si la raz??n social ya existe, no se pisar??.)</small>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <h6>Razones sociales:</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach ($user->empresa->razones_sociales as $razon_social)
                                        <div class="col-3">
                                            <div class="card text-center">
                                                <div class="card-header">
                                                    <b>{{ $razon_social->nombre }}</b>
                                                </div>
                                                <div class="card-body">
                                                    @if ( $razon_social->rut )
                                                        <p>Rut: <b>{{ $razon_social->rut }}</b></p>
                                                    @endif
                                                    @if ( $razon_social->ciudad )
                                                        <p>Ciudad: <b>{{ $razon_social->ciudad }}</b></p>
                                                    @endif
                                                    @if ( $razon_social->codigo_postal )
                                                        <p>C??digo postal: <b>{{ $razon_social->codigo_postal }}</b></p>
                                                    @endif
                                                    @if ( $razon_social->direccion )
                                                        <p>Direcci??n: <b>{{ $razon_social->direccion }}</b></p>
                                                    @endif
                                                    @if ( $razon_social->numero_de_cuenta_bancaria )
                                                        <p>N?? Cuenta Bancaria: <b>{{ $razon_social->numero_de_cuenta_bancaria }}</b></p>
                                                    @endif
                                                    @if ( $razon_social->banco )
                                                        <p>Banco: <b>{{ $razon_social->banco }}</b></p>
                                                    @endif
                                                    @if ( $razon_social->tipo_de_cuenta )
                                                        <p>Cuenta: <b>{{ $razon_social->tipo_de_cuenta }}</b></p>
                                                    @endif
                                                    <a href="{{ route('admin.razones-sociales.show', ['id' => $razon_social->id]) }}" class="btn btn-sm btn-info">
                                                        Ver
                                                    </a>
                                                    <button class="btn btn-sm btn-danger" onclick="sweetAlertToRemoveRazonSocial('button_submit_to_remove_rs_{{$razon_social->id}}')" type="button">
                                                        Borrar
                                                    </button>
                                                    <form action="{{ route('admin.razones-sociales.destroy', ['id' => $razon_social->id]) }}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('delete')
                                                        <input type="submit" id="button_submit_to_remove_rs_{{$razon_social->id}}">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                    @endif

                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        function sweetAlertToRemoveRazonSocial( boton_submit ) {
            Swal.fire({
                icon: "warning",
                title: '??Desea ELIMINAR esta Raz??n social?',
                confirmButtonText: `Eliminar`,
                confirmButtonColor: '#d9534f',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    console.log(boton_submit)
                    document.getElementById(boton_submit).click();
                }
            })
        }
    </script>
@endsection
