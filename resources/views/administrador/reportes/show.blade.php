@extends('layouts.app')

@section('content')

    @include('includes.messages_in_session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Reporte <b>{{ $reporte->titulo }}</b></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form>

                                <div class="form-group">
                                    <label for="descripcion">Nombre</label>
                                    <textarea disabled class="form-control" id="descripcion" rows="3">{{ $reporte->descripcion }}</textarea>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- End card -->
        </div>
    </div>
@endsection
