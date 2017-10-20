@extends('layouts.material')

@section('content')


    <list api="{{ $listsRoute}}" token="{{$token}}"></list>



@endsection