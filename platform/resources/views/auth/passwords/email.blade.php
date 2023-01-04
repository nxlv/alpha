@extends('layouts.auth')

@section('content')
    <header>
        <h1>{{ __( 'Password Recovery' ) }}</h1>
    </header>
    <section>
        @if ( session( 'status' ) )
            <div class="alert alert-success" role="alert">
                {{ session( 'status' ) }}
            </div>
        @endif

        <form method="POST" action="{{ route( 'password.email' ) }}">
            @csrf

            <div class="alpha__fields">
                <div class="alpha__fields-field">
                    <input id="email" type="email" class="@error( 'email' ) is-invalid @enderror" name="email" value="{{ old( 'email' ) }}" required autocomplete="email" autofocus>
                    <label for="email"><strong>E-mail</strong></label>
                    @error( 'email' )
                    <span class="validation" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="alpha__fields-field alpha__fields-field--buttons">
                    <button type="submit" class="btn btn-primary">{{ __( 'Send Reset Link' ) }}</button>
                </div>
            </div>

            @guest
            <footer>
                @if ( Route::has( 'password.request' ) )
                    <a class="btn btn-link" href="{{ route( 'password.request' ) }}">{{ __( 'Forgot Your Password?' ) }}</a>
                @endif

                @if ( Route::has( 'login' ) )
                    <a class="btn btn-link" href="{{ route( 'login' ) }}">{{ __( 'Login' ) }}</a>
                @endif

                @if ( Route::has( 'register' ) )
                    <a class="btn btn-link" href="{{ route( 'register' ) }}">{{ __( 'Register' ) }}</a>
                @endif
            </footer>
            @endguest
        </form>
    </section>
@endsection
