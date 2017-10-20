@extends('layouts.app')

@section('content')


    <list api="{{ $listsRoute}}" token="{{$token}}"></list>



@endsection