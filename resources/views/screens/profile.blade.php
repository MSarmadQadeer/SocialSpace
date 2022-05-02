@extends("layouts.layout")

@section('title', 'SocialSpace - Profile')

@section('content')

    <div class="d-md-flex">
        <div class="profile-page-container col-md-6">
            <div class="profile-page w-100 py-3 px-4 d-flex flex-column align-items-center">

                <div class="{{ $personData['owner'] == 1 ? 'profile-img-container' : 'view-profile-image' }}">
                    <label for="profile-img-input">
                        <img class="profile-pic {{ $personData['owner'] == 1 ? '' : 'zoom' }}"
                            src="{{ $personData['profile_pic'] ? $personData['profile_pic'] : 'img/no-image.png' }}"
                            alt="">

                        @if ($personData['owner'] == 1)
                            <input type="file" name="profile-img" id="profile-img-input" class="d-none"
                                accept=".jpg, .jpeg, .png">
                        @endif

                        <div class="camera-svg-container hide">
                            <svg id="camera" viewBox="0 0 512 512">
                                <path
                                    d="M512 144v288c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48h88l12.3-32.9c7-18.7 24.9-31.1 44.9-31.1h125.5c20 0 37.9 12.4 44.9 31.1L376 96h88c26.5 0 48 21.5 48 48zM376 288c0-66.2-53.8-120-120-120s-120 53.8-120 120 53.8 120 120 120 120-53.8 120-120zm-32 0c0 48.5-39.5 88-88 88s-88-39.5-88-88 39.5-88 88-88 88 39.5 88 88z">
                                </path>
                            </svg>
                        </div>
                    </label>
                </div>

                <div>
                    <p class="profile-user-name">{{ $personData['person_name'] }}</p>
                </div>

                <div class="bio">
                    <p class="bio-text">{{ $personData['bio'] }}</p>
                    <textarea class="hide" name="" id="" cols="30" rows="10"></textarea>
                    <p class="bio-length-checker hide"> / 350</p>

                    @if ($personData['owner'] == 1)
                        <div class="edit-and-save-bio-btns">
                            <div class="edit-bio-btn">Edit Bio</div>
                            <div class="save-bio-btn hide">Save</div>
                        </div>
                    @endif
                </div>

                {{-- <div><span>Gender : </span>{{ $personData["gender"] }}</div> --}}

            </div>
        </div>

        <div class="col-md-6">
            <div class="nav-of-profile-posts">{{ $personData['owner'] == 1 ? 'Your' : $personData['person_name'] . "'s" }}
                Posts</div>
            <div class="profile-posts-container posts-container">
                @if (count($personPosts) == 0)
                    <div class="my-5 px-3 no-posts-message">
                        {{ $personData['owner'] == 1 ? 'You' : $personData['person_name'] }} didn't post anything yet.
                    </div>
                @endif
                @foreach ($personPosts as $post)
                    @include('components.post', ['post' => $post])
                @endforeach
            </div>
        </div>

    </div>


    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
    <script src="{{ asset('js/croppie.min.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>

@endsection
