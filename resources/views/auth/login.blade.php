@extends('layouts.material')

@section('content')
<md-layout class="md-align-center">
    <md-card>
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <md-card-header>
                <div class="md-title">Login</div>
            </md-card-header>

            <md-card-content>

                    <md-input-container>
                        <label>E-Mail Address</label>
                        <md-input name="email" type="email" required></md-input>
                    </md-input-container>
                    <md-input-container>
                        <label>Passwort</label>
                        <md-input name="password" required type="password"></md-input>
                    </md-input-container>

            </md-card-content>

            <md-card-actions>
                <md-button class="md-primary" type="submit">Login</md-button>
            </md-card-actions>
        </form>
    </md-card>
</md-layout>
@endsection
