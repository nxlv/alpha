@extends('layouts.auth')

@section('content')
    <header>
        <h1>{{ __( 'E-mail Verification' ) }}</h1>

        <div class="alert alert-success" role="alert">
            <p>{{ __('A fresh verification link has been sent to your email address.') }}</p>
        </div>
    </header>
    <section>
        <form method="POST" action="{{ route( 'verification.resend' ) }}">
            @csrf

            <p>{{ __( 'Before proceeding, please check your email for a verification link.' ) }}</p>
            <p>{{ __( 'If you did not receive the email, click <strong>Re-send</strong> below.' ) }},</p>

            <div class="alpha__fields">
                <div class="alpha__fields-field alpha__fields-field--buttons">
                    <button type="submit" class="btn btn-primary">{{ __( 'Re-send' ) }}</button>
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


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
