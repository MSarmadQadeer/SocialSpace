<div class="post" data-id="{{ $post['id'] }}">
    <div class="w-100 d-flex justify-content-between flex-row-reverse align-items-center">

        @if ($post['own'] == 1)
            <!-- Default dropstart button -->
            <div class="dropstart me-3">
                {{-- <div class="dots-icon"><div></div></div> --}}
                <button type="button" class="three-dot-icon-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg x="0px" y="0px" viewBox="0 0 426.667 426.667"
                        style="enable-background:new 0 0 426.667 426.667;" xml:space="preserve">
                        <g>
                            <g>
                                <circle cx="42.667" cy="213.333" r="42.667" />
                            </g>
                        </g>
                        <g>
                            <g>
                                <circle cx="213.333" cy="213.333" r="42.667" />
                            </g>
                        </g>
                        <g>
                            <g>
                                <circle cx="384" cy="213.333" r="42.667" />
                            </g>
                        </g>
                    </svg>
                </button>
                <ul class="dropdown-menu">
                    <!-- Dropdown menu links -->
                    <li class="edit-post-tab"><a class="dropdown-item" href="#">Edit</a></li>
                    <li class="delete-post-tab"><a class="dropdown-item" href="#">Delete</a></li>
                </ul>
            </div>
        @endif

        <div class="post-owner">
            <div>
                <img class="zoom"
                    src="{{ $post['profile_pic'] ? $post['profile_pic'] : 'img/no-image.png' }}" alt="">
            </div>
            <div>
                <p class="person-name" data-person-id="{{ $post['person_id'] }}">{{ $post['person_name'] }}</p>
                <p>{{ $post['date'] }}</p>
            </div>
        </div>
    </div>

    <div class="caption">
        {{ $post['caption'] }}
    </div>

    <div class="post-image-container">
        @foreach ($post['images'] as $image)
            <img class="zoom" src="{{ $image['image'] }}" alt="">
        @endforeach
    </div>

    <div class="likes-panel">
        <div class="likes-panel-icons">
            <div class="d-flex">
                <div class="unchecked-heart {{ $post['current_person_liked'] == 1 ? 'hide' : '' }}">
                    <svg viewBox="0 0 512 512">
                        <path
                            d="M458.4 64.3C400.6 15.7 311.3 23 256 79.3 200.7 23 111.4 15.6 53.6 64.3-21.6 127.6-10.6 230.8 43 285.5l175.4 178.7c10 10.2 23.4 15.9 37.6 15.9 14.3 0 27.6-5.6 37.6-15.8L469 285.6c53.5-54.7 64.7-157.9-10.6-221.3zm-23.6 187.5L259.4 430.5c-2.4 2.4-4.4 2.4-6.8 0L77.2 251.8c-36.5-37.2-43.9-107.6 7.3-150.7 38.9-32.7 98.9-27.8 136.5 10.5l35 35.7 35-35.7c37.8-38.5 97.8-43.2 136.5-10.6 51.1 43.1 43.5 113.9 7.3 150.8z">
                        </path>
                    </svg>
                </div>
                <div class="checked-heart {{ $post['current_person_liked'] == 0 ? 'hide' : '' }}">
                    <svg viewBox="0 0 512 512">
                        <path
                            d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z">
                        </path>
                    </svg>
                </div>
                <div class="like-text ms-2">
                    <p class=""><span class="likes-count">{{ $post['likes_count'] }}</span> Likes</p>
                </div>
            </div>

            <div class="comment-icon">
                <svg viewBox="0 0 512 512">
                    <path
                        d="M256 32C114.6 32 0 125.1 0 240c0 47.6 19.9 91.2 52.9 126.3C38 405.7 7 439.1 6.5 439.5c-6.6 7-8.4 17.2-4.6 26S14.4 480 24 480c61.5 0 110-25.7 139.1-46.3C192 442.8 223.2 448 256 448c141.4 0 256-93.1 256-208S397.4 32 256 32zm0 368c-26.7 0-53.1-4.1-78.4-12.1l-22.7-7.2-19.5 13.8c-14.3 10.1-33.9 21.4-57.5 29 7.3-12.1 14.4-25.7 19.9-40.2l10.6-28.1-20.6-21.8C69.7 314.1 48 282.2 48 240c0-88.2 93.3-160 208-160s208 71.8 208 160-93.3 160-208 160z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <hr>
    <div class="write-comment">
        <input class="comment-text" type="text" name="comment" placeholder="Write a comment ..." autocomplete="off">
    </div>
    <hr>

    <div class="view-comment">
        <p><span>{{ count($post['comments']) }}</span> Comments</p>
        @if (count($post['comments']) > 0)
            <div class="chevron">
                <svg class="chevron-down" viewBox="0 0 448 512">
                    <path
                        d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z">
                    </path>
                </svg>
                <svg class="chevron-up hide" viewBox="0 0 448 512">
                    <path
                        d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z">
                    </path>
                </svg>
            </div>
        @endif
    </div>

    <div class="comments-panel hide">
        @foreach ($post['comments'] as $comment)
            <div class="comment">
                <div>
                    <img class="zoom"
                        src="{{ $comment['profile_pic'] ? $comment['profile_pic'] : 'img/no-image.png' }}" alt="">
                </div>
                <div>
                    <p class="person-name" data-person-id="{{ $comment['person_id'] }}">
                        {{ $comment['person_commented'] }}</p>
                    <p>{{ $comment['comment_message'] }}</p>
                </div>
            </div>
        @endforeach

    </div>
</div>
