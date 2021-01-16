@extends('admin-layouts.app')

@section('content')
    <div class="container-fluid" id="app">
      <div class="row">
        @include('admin-layouts.sidebar')
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
          <purchasing-list></purchasing-list>
        </main>
      </div>
    </div>
@endsection