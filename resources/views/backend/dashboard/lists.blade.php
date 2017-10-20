@extends('layouts.material')

@section('content')
    <grid :header="[{'name':'id','title':'#'},{'name':'token','title':'Template Name'}]" api="{{ $tokensRoute }}" links="{{ $links }}"></grid>
@endsection