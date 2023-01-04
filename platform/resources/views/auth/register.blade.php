@extends('layouts.auth')

@section('content')
    <header>
        <h1>{{ __( 'Create an Account' ) }}</h1>
    </header>
    <section>
        <form method="POST" action="{{ route( 'register' ) }}">
            @csrf

            <div class="alpha__fields">
                <div class="alpha__fields-field">
                    <input id="name" type="text" class="@error( 'name' ) is-invalid @enderror" name="name" value="{{ old( 'name' ) }}" required autocomplete="name" autofocus>
                    <label for="name"><strong>Your Name</strong></label>
                    @error( 'name' )
                    <span class="validation" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="alpha__fields-field">
                    <input id="email" type="email" class="@error( 'email' ) is-invalid @enderror" name="email" value="{{ old( 'email' ) }}" required autocomplete="email" autofocus>
                    <label for="email"><strong>E-mail</strong></label>
                    @error( 'email' )
                    <span class="validation" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="alpha__fields-field">
                    <input id="password" type="password" class="@error( 'password' ) is-invalid @enderror" name="password" required autocomplete="new-password">
                    <label for="password"><strong>Password</strong></label>
                    @error( 'password' )
                    <span class="validation" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="alpha__fields-field">
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                    <label for="password-confirm"><strong>Password (Confirm)</strong></label>
                </div>
                <div class="alpha__fields-field alpha__fields-field--buttons">
                    <button type="submit" class="btn btn-primary">{{ __( 'Register' ) }}</button>
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
