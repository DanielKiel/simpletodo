@extends('layouts.material')

{{--'href' => route('backend.list', ['token' => '_id_']),--}}
{{--'title' => 'view',--}}
{{--'icon' => 'subject',--}}
{{--'class' => '',--}}
{{--'ajax' => false,--}}
{{--'replace' => 'token'--}}


@section('content')
    <md-layout class="md-align-center">
        <md-card>
            <md-card-header>
                <div class="md-title">
                    Listen
                </div>
            </md-card-header>
            <md-card-content>
                <grid :toolbar="[{'href':'/backend/list', 'title':'create', 'icon':'add', 'class': 'md-icon-button md-fab md-mini', 'ajax':false}]"
                      :header="[{'name':'id','title':'#'},{'name':'token','title':'Template Name'}]"
                      api="{{ $tokensRoute }}"
                      links="{{ $links }}"></grid>
            </md-card-content>
        </md-card>
    </md-layout>
@endsection