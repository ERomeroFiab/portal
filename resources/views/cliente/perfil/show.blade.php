@extends('layouts.app')

@section('customcss')

@endsection

@section('content')

    @include('includes.messages_in_session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ $user->name }}</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('cliente.perfil.update',['id' =>  $user->id]) }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Nueva Contraseña</label>
                                    <input name="password" type="password" class="form-control" id="name" value="">
                                    
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <input class="btn btn-sm btn-success" type="submit" value="Cambiar Contraseña">
                            </div>

                        </div>
                    </form>

                </div>

            </div> <!-- End card -->
        </div>
    </div>
@endsection

@section('customjs')

@endsection
