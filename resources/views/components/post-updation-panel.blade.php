<div class="position-fixed top-0 bottom-0 start-0 end-0 post-updation-screen hide">
    <div class="position-relative w-100 h-100">
        <div class="position-absolute top-0 bottom-0 start-0 end-0 d-flex justify-content-center overflow-auto">

            <div class="post-updation-panel m-auto w-100">
                <div>
                    <div class="component-header">
                        <div class="update-post-text">Update post</div>
                        <div class="close-icon close-post-updation-btn">
                            <svg viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" />
                            </svg>
                        </div>
                    </div>
                    <hr>
                    <form action="/update-post" method="POST" name="post-updation-form" enctype="multipart/form-data">
                        @csrf
                        <textarea placeholder="What's on your mind?" name="caption" class="post-text-caption"></textarea>
                        <input type="text" name="id" class="id-text d-none">
                        <input type="file" name="image" id="update-image-file" class="d-none"
                            accept=".jpg, .jpeg, .png">
                        <label class="images-to-post-block" for="update-image-file">
                            <div class="add-image-to-post">Add Image to your Post.</div>
                            <div class="image-icon">
                                <svg id="images" viewBox="0 0 576 512">
                                    <path
                                        d="M480 416v16c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V176c0-26.51 21.49-48 48-48h16v208c0 44.112 35.888 80 80 80h336zm96-80V80c0-26.51-21.49-48-48-48H144c-26.51 0-48 21.49-48 48v256c0 26.51 21.49 48 48 48h384c26.51 0 48-21.49 48-48zM256 128c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-96 144l55.515-55.515c4.686-4.686 12.284-4.686 16.971 0L272 256l135.515-135.515c4.686-4.686 12.284-4.686 16.971 0L512 208v112H160v-48z">
                                    </path>
                                </svg>
                            </div>
                        </label>
                        <div onClick="document.forms['post-updation-form'].submit();" class="button update-btn">Update
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
