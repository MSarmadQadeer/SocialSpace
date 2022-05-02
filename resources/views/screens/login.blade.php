@extends("layouts.login-signup")


@section('title', 'SocialSpace - Log In')


@section('card')

    @include('components.login-card')

    <script src="{{ asset('js/login.js') }}"></script>

@endsection
