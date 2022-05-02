@extends("layouts.login-signup")


@section('title', 'SocialSpace - Sign Up')


@section('card')

    @include('components.signup-card')

    <script src="{{ asset('js/signup.js') }}"></script>

@endsection
