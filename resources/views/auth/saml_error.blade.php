@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card danger">
                    <div class="card-header">Innloggingsproblem</div>

                    <p>Beklager, det oppsto et innloggingsproblem.</p>

                    <ul>
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                        @foreach ($error_details as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
    </div>
@endsection
