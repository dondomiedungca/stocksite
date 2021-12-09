@extends('layouts.app')

@section('content')
    <div id="app">
      <app>
        @yield('body')
      </app>
    </div>
@endsection

@section('js')
    <script src="{{ url('js/app.js') }}"></script>      
@endsection

@section('style')
    <link rel="stylesheet" href="{{ url('css/app.css') }}">
@endsection