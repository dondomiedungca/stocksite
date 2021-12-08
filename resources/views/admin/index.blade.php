@extends('admin-layouts.app')

@section('content')
    <div id="app">
      <v-app>
        <appbar-vue></appbar-vue>
        <sidebar-vue height="100"></sidebar-vue>
        @yield('body')
        <snackbar-vue></snackbar-vue>
      </v-app>
    </div>
@endsection

@section('js')
    <script src="{{ url('js/app.js') }}"></script>      
@endsection

@section('style')
    <link rel="stylesheet" href="{{ url('css/app.css') }}">
@endsection