@extends('layouts.material')

@section('content')

    @if( is_null($token) )
        <md-layout class="md-align-center">
            <md-card>
                <md-card-content>
                    <list-form method="POST" action="{{route('lists.store')}}" :el="{{new \App\Lists([
                            'title' => null, 'token'=> null, 'description'=> null
                        ])}}" ></list-form>
                </md-card-content>
            </md-card>
        </md-layout>
    @else
        <list api="{{ $listsRoute}}" token="{{$token}}"></list>
    @endif

@endsection