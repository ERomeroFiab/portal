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

                </div>

            </div> <!-- End card -->
        </div>
    </div>
@endsection

@section('customjs')

@endsection
