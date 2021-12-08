@extends('admin-layouts.app')

@section('content')
    <form class="form-signin" action="/admin/signin" method="POST">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">
            <center>Please sign in</center>
        </h1>
        
        @if(Session::has('error'))
            <p class="alert alert-danger">{{ Session::get('error') }}</p>
        @endif

        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" id="inputEmail" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email address" autofocus>
        <br>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control @error('password') is-invalid @enderror" placeholder="Password">

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} value="remember-me"> Remember me
            </label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">
            <center>&copy; Stock Site</center>
        </p>
    </form>  
@endsection