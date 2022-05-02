@extends("layouts.layout")

@section('title', 'SocialSpace - Home')

@section('content')

    <div class="">
        <div class="row g-0">

            <div class="col-12 posts-container">
                @foreach ($postsData as $post)
                    @include('components.post', ['post' => $post])
                @endforeach
            </div>

        </div>
    </div>


    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>

@endsection
