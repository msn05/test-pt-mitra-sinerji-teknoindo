@extends('adminlte::page')

@section(config('adminlte::title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0"><b>{{ Auth::user()->name }}</b> {{ __('success login in sistem') }}</p>
                </div>
            </div>
        </div>
    </div>
@stop
