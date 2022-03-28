@extends('layouts.app')

@section('content')

    @include('includes.messages_in_session')
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Empresa <b>{{ $empresa->nombre }}</b></h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.empresas.update', ['id' =>  $empresa->id]) }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input name="nombre" type="text" class="form-control" id="name" value="{{ $empresa->nombre }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <input class="btn btn-sm btn-success" type="submit" value="Editar">
                            </div>

                        </div>
                    </form>
                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection