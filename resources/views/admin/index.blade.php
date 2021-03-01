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
    <script src="{{ url('js/vue/components/product/src/index.js') }}"></script>      
@endsection

@section('style')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/style.css') }}">
@endsection