let totalLengthOfBio = 512;


// ======================
// Listening Change Event
// ======================
window.addEventListener("change", (e) => {
    if (e.target.closest("#image-file")) {
        let filename = "Add Image to your Post.";
        if (e.target.closest("#image-file").files[0]) {
            filename = e.target.closest("#image-file").files[0].name;
        }
        e.target.closest("#image-file").closest("form").querySelector(".add-image-to-post").innerText = filename;
    }

    else if (e.target.closest("#update-image-file")) {
        let filename = "Add Image to your Post.";
        if (e.target.closest("#update-image-file").files[0]) {
            filename = e.target.closest("#update-image-file").files[0].name;
        }
        e.target.closest("#update-image-file").closest("form").querySelector(".add-image-to-post").innerText = filename;
    }
})


// =======================
// Listening KeyDown Event
// =======================
window.addEventListener("keydown", (e) => {
    if (e.target.closest(".bio textarea") && e.keyCode != 8) {
        // 8 is the keyCode of Backspace key.
        if (e.target.closest(".bio textarea").value.length >= totalLengthOfBio) {
            e.preventDefault();
        }
    }
})


// =====================
// Listening KeyUp Event
// =====================
window.addEventListener("keyup", (e) => {
    if (e.target.classList.contains("comment-text") && e.keyCode === 13) {
        if (getCookie("person_id")) {
            var data = {
                postID: e.target.closest(".post").dataset.id,
                comment: e.target.value,
            };
            e.target.value = "";

            $.ajax({
                type: "POST",
                url: '/comment',
                data: data,
                success: function () {
                    location.reload();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
        else {
            location.href = '/login'
        }
    }

    else if (e.target.closest(".bio textarea")) {
        e.target.closest(".bio textarea").nextElementSibling.innerText = `${e.target.closest(".bio textarea").value.length} / ${totalLengthOfBio}`
    }
})


// =====================
// Listening Click Event
// =====================
window.addEventListener("click", async (e) => {
    if (e.target.closest(".log-out")) {
        document.cookie = "person_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        window.location = '/login';
    }

    // else if (e.target.closest(".developer-profile")) {
    //     window.location = `/profile-${1}`;
    // }

    else if (e.target.closest(".person-name")) {
        window.location = `/profile-${e.target.closest(".person-name").dataset.personId}`;
    }

    else if (e.target.closest(".comment-icon")) {
        if (getCookie("person_id")) {
            e.target.closest(".comment-icon").closest(".post").querySelector(".write-comment input").focus();
        }
        else {
            location.href = '/login'
        }
    }

    else if (e.target.closest(".chevron")) {
        e.target.closest(".chevron").firstElementChild.classList.toggle("hide");
        e.target.closest(".chevron").lastElementChild.classList.toggle("hide");
        e.target.closest(".chevron").closest(".post").querySelector(".comments-panel").classList.toggle("hide");
    }

    else if (e.target.closest(".unchecked-heart")) {
        if (getCookie("person_id")) {
            e.target.closest(".unchecked-heart").classList.add("hide");
            e.target.closest(".unchecked-heart").nextElementSibling.classList.remove("hide");
            const likesCount = e.target.closest(".unchecked-heart").nextElementSibling.nextElementSibling.querySelector(".likes-count");
            likesCount.innerText = Number(likesCount.innerText) + 1;

            var data = {
                postID: e.target.closest(".post").dataset.id
            };

            $.ajax({
                type: "POST",
                url: '/like',
                data: data,
                success: function () {
                    console.log("liked");
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
        else {
            location.href = '/login'
        }
    }

    else if (e.target.closest(".checked-heart")) {
        if (getCookie("person_id")) {
            e.target.closest(".checked-heart").classList.add("hide");
            e.target.closest(".checked-heart").previousElementSibling.classList.remove("hide");
            const likesCount = e.target.closest(".checked-heart").nextElementSibling.querySelector(".likes-count");
            likesCount.innerText = Number(likesCount.innerText) - 1;

            var data = {
                postID: e.target.closest(".post").dataset.id
            };

            $.ajax({
                type: "POST",
                url: '/unlike',
                data: data,
                success: function () {
                    console.log("unliked");
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
        else {
            location.href = '/login'
        }
    }

    else if (e.target.closest(".close-profile-pic-updation-btn")) {
        e.target.closest(".profile-pic-upload-screen").classList.add("hide");
        document.querySelector("body").classList.remove("noscroll");
    }

    else if (e.target.closest(".close-post-updation-btn")) {
        e.target.closest(".post-updation-screen").classList.add("hide");
        document.querySelector("body").classList.remove("noscroll");
    }

    else if (e.target.closest(".close-post-creation-btn")) {
        e.target.closest(".post-creation-screen").classList.add("hide");
        document.querySelector("body").classList.remove("noscroll");
    }

    else if (e.target.closest(".delete-post-tab")) {
        if (getCookie("person_id")) {
            await $.ajax({
                type: "POST",
                url: '/delete-post',
                data: {
                    postID: e.target.closest(".post").dataset.id
                },
                success: function () {
                    console.log("post deleted");
                    location.reload();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
        else {
            location.href = '/login'
        }
    }

    else if (e.target.closest(".edit-post-tab")) {
        if (getCookie("person_id")) {
            let data_we_want_to_edit;

            await $.ajax({
                type: "GET",
                url: `/post-${e.target.closest(".post").dataset.id}`,
                success: function (response) {
                    data_we_want_to_edit = response;
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const postUpdationScreen = document.querySelector(".post-updation-screen");
            postUpdationScreen.querySelector(".post-text-caption").value = data_we_want_to_edit["caption"];
            postUpdationScreen.querySelector(".id-text").value = e.target.closest(".post").dataset.id;

            if (data_we_want_to_edit["image"]) {
                postUpdationScreen.querySelector(".add-image-to-post").innerText = data_we_want_to_edit["image"];
            }

            postUpdationScreen.classList.remove("hide");
            document.querySelector("body").classList.add("noscroll");
        }
        else {
            location.href = '/login'
        }
    }

    else if (e.target.closest(".create-post-icon")) {
        if (getCookie("person_id")) {
            document.querySelector(".post-creation-screen").classList.remove("hide");
            document.querySelector("body").classList.add("noscroll");
        }
        else {
            location.href = '/login'
        }
    }

    else if (e.target.closest(".profile-icon")) {
        if (getCookie("person_id")) {
            location.href = `/profile-${getCookie("person_id")}`;
        }
        else {
            location.href = '/login'
        }
    }

    else if (e.target.closest(".header-logo")) {
        location.href = '/home';
    }

    else if (e.target.closest(".home-icon")) {
        location.href = '/home';
    }

    else if (e.target.closest(".edit-bio-btn")) {
        if (getCookie("person_id")) {
            profilePage.querySelector(".bio textarea").value = profilePage.querySelector(".bio-text").innerText;
            profilePage.querySelector(".bio-text").classList.add("hide");
            profilePage.querySelector(".bio textarea").classList.remove("hide");
            profilePage.querySelector(".bio-length-checker").innerText = `${profilePage.querySelector(".bio textarea").value.length} / ${totalLengthOfBio}`
            profilePage.querySelector(".bio-length-checker").classList.remove("hide");
            profilePage.querySelector(".save-bio-btn").classList.remove("hide");
            e.target.closest(".edit-bio-btn").classList.add("hide");
        }
        else {
            location.href = '/login'
        }
    }

    else if (e.target.closest(".save-bio-btn")) {
        if (getCookie("person_id")) {
            if (profilePage.querySelector(".bio textarea").value.length <= totalLengthOfBio) {
                profilePage.querySelector(".bio-text").innerText = profilePage.querySelector(".bio textarea").value;
                profilePage.querySelector(".bio-text").classList.remove("hide");
                profilePage.querySelector(".bio textarea").classList.add("hide");
                profilePage.querySelector(".bio-length-checker").classList.add("hide");
                e.target.closest(".save-bio-btn").classList.add("hide");
                profilePage.querySelector(".edit-bio-btn").classList.remove("hide");

                $.ajax({
                    type: "POST",
                    url: '/edit-bio',
                    data: {
                        bio: profilePage.querySelector(".bio-text").innerText
                    },
                    success: function (response) {

                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            }
            else {
                alert(`Bio should in range of ${totalLengthOfBio} characters.`)
            }
        }
        else {
            location.href = '/login'
        }
    }
})

mediumZoom(".zoom", {
    margin: 20,
    scrollOffset: 20,
    background: "rgba(233, 236, 255, 0.692)"
})

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}
