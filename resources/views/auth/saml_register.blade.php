@extends('layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Velkommen til UB-baser</div>
                    <div class="card-body">
                        <p>
                            Hei, det ser ikke ut som du har brukt UB-baser før.
                            Er det greit at vi lagrer følgende informasjon om deg?
                        </p>
                        <ul>
                            <li>Feide-ID: <strong>{{ $data['saml_id'] }}</strong> (for å identifisere deg)</li>
                            <li>Navn: <strong>{{ $data['name'] }}</strong> (for å identifisere deg)</li>
                            <li>E-post: <strong>{{ $data['email'] }}</strong> (for å kunne kontakte deg ved eventuelle viktige endringer i tjenesten)</li>
                        </ul>
                        <form action="{{ action('Auth\LoginController@samlStoreNewUser') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success">Det er greit</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
