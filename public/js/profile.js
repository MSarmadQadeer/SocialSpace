const profilePageContainer = document.querySelector('.profile-page-container');
const navBar = document.querySelector("header");
const nav_of_profile_posts = document.querySelector(".nav-of-profile-posts");

function profile_page_resize_adjustments() {
    if (window.innerWidth >= 768) {
        profilePageContainer.style.position = "sticky";
        profilePageContainer.style.height = `${window.innerHeight - navBar.offsetHeight}px`;
    } else {
        profilePageContainer.style.position = "static";
        profilePageContainer.style.height = "100%";
    }
    profilePageContainer.style.top = navBar.offsetHeight + "px";
    nav_of_profile_posts.style.top = navBar.offsetHeight + "px";
}
profile_page_resize_adjustments();


const profileImgContainer = document.querySelector(".profile-img-container");
const cameraSvgOnProfilePic = document.querySelector(".profile-img-container .camera-svg-container svg");

function adjustCameraSvgHeight() {
    if (profileImgContainer) {
        cameraSvgOnProfilePic.style.width = `${profileImgContainer.offsetWidth / 5}px`;
    }
}
adjustCameraSvgHeight();


window.addEventListener("resize", () => {
    adjustCameraSvgHeight();
    profile_page_resize_adjustments();
});


const profilePage = document.querySelector(".profile-page");
profilePage.addEventListener("mouseover", (e) => {
    if (e.target.closest(".profile-img-container")) {
        e.target.closest(".profile-img-container").style.border = "3px solid rgb(94, 207, 252)";
        e.target.closest(".profile-img-container").querySelector(".camera-svg-container").classList.remove("hide");
    }
});

profilePage.addEventListener("mouseout", (e) => {
    if (e.target.closest(".profile-img-container")) {
        e.target.closest(".profile-img-container").style.border = "none";
        e.target.closest(".profile-img-container").querySelector(".camera-svg-container").classList.add("hide");
    }
});


/*
    If the current person is the owner ("owner"==1) of the profile that he/she is viewing than
    it means that the .profile-img-container class exists and he/she can change his/her
    profile pic else not.
*/

const profile_pic_upload_screen = document.querySelector(".profile-pic-upload-screen")
if (profileImgContainer) {
    $profile_croppie = $("#croppie-applied").croppie({
        url: 'img/white_screen.jpg',
        enableExif: true,
        viewport: {
            width: 210,
            height: 210,
            type: 'circle'
        },
        boundary: {
            width: 300,
            height: 300
        }
    });

    const profile_image_input = document.getElementById('profile-img-input');
    profile_image_input.addEventListener("change", (event) => {
        let reader = new FileReader();
        reader.onload = function (e) {

            $profile_croppie.croppie("bind", {
                url: e.target.result
            })
            profile_pic_upload_screen.classList.remove("hide");
            document.querySelector("body").classList.add("noscroll");
        }
        reader.readAsDataURL(profile_image_input.files[0]);
    });
}

window.addEventListener("click", (e) => {
    if (e.target.closest(".update-profile-image-btn")) {
        if (getCookie("person_id")) {
            $profile_croppie.croppie("result", {
                type: "canvas",
                size: "viewport"
            }).then(function (response) {
                profile_pic_upload_screen.classList.add("hide");
                document.querySelector("body").classList.remove("noscroll");
                $.ajax({
                    type: "POST",
                    url: '/upload-profile-img',
                    data: {
                        "image": response
                    },
                    success: function () {
                        location.reload();
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            })
        }
        else {
            location.href = '/login'
        }
    }
})
