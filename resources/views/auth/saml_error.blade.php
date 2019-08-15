@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card bg-danger text-white">
                    <div class="card-header">Innloggingsproblem</div>

                    <p>Beklager, det oppsto et innloggingsproblem.</p>
                    <p>{{ $error }}</p>

                </div>
            </div>
        </div>
    </div>
@endsection
