@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('css/bt.css') }}" />
    @include('includes.messages_in_session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2>Nueva Empresa</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('admin.empresas.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input name="name" type="text" class="form-control" id="name"
                                        aria-describedby="name_error" placeholder="Ejemplo: Wallmart">
                                    @if ($errors->has('name'))
                                        <small id="name_error"
                                            class="form-text text-muted text-danger">{{ $errors->first('name') }}</small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection
