@extends('layouts.auth')

@section('content')
    <header>
        <h1>{{ __( 'Login' ) }}</h1>
    </header>
    <section>
        <form method="POST" action="{{ route( 'login' ) }}">
            @csrf

            <div class="alpha__fields">
                <div class="alpha__fields-field">
                    <input id="email" type="email" class="@error( 'email' ) is-invalid @enderror" name="email" value="{{ old( 'email' ) }}" required autocomplete="email" autofocus>
                    <label for="email"><strong>E-mail</strong></label>
                    @error( 'email' )
                    <span class="validation" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="alpha__fields-field">
                    <input id="password" type="password" class="@error( 'password' ) is-invalid @enderror" name="password" required autocomplete="current-password">
                    <label for="password"><strong>Password</strong></label>
                    @error( 'password' )
                    <span class="validation" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="alpha__fields-field alpha__fields-field--boxes">
                    <input type="checkbox" name="remember" id="remember" {{ old( 'remember' ) ? 'checked' : '' }}>
                    <label for="remember">Stay logged in on this device?</label>
                </div>
                <div class="alpha__fields-field alpha__fields-field--buttons">
                    <button type="submit" class="btn btn-primary">{{ __( 'Login' ) }}</button>
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
