const signupForm = document.querySelector(".signup-form");
const signupBtn = document.querySelector(".signup-btn");
const login_btn_on_signup_page = document.querySelector(".login-btn-on-signup-page");


// =============================================
// These Below Variables are for Form Validation
// =============================================
const normalBorder = "solid 1px rgb(233, 236, 255)";
const defaulterBorder = "solid 1px rgb(191, 0, 255)";
let notEmpty = false;
let passwordVerified = false;
let emailAlreadyExist = 0;


login_btn_on_signup_page.addEventListener("click", (e) => {
    e.preventDefault();
    window.location = '/login';
})


// ==================================================
// Logic For The Selection of Gender From Signup Form
// ==================================================
const gendersPanel = document.querySelector(".genders-panel");
const genders = document.querySelectorAll(".gender");
let gender = "custom"; // default gender is custom
gendersPanel.addEventListener("click", (e) => {
    e.preventDefault();
    if (e.target.closest(".gender")) {
        genders.forEach((gender) => {
            gender.lastElementChild.checked = false;
        })
        e.target.closest(".gender").lastElementChild.checked = true;
        gender = e.target.closest(".gender").dataset.gender;
    }
})



const allTextBoxes = document.querySelectorAll(".text-box");
signupBtn.addEventListener("click", async (e) => {
    let i = 0;
    /*------------------------------------------------
    i finds out the number of textboxes that are empty
    ------------------------------------------------*/
    allTextBoxes.forEach((textBox) => {
        if (textBox.value == "") {
            textBox.style.border = defaulterBorder;
            textBox.previousElementSibling.innerText = "Cannot Be Empty";
            notEmpty = false;
            i++;
        }
    })
    if (i === 0) notEmpty = true;

    if (notEmpty && passwordVerified && !emailAlreadyExist) {
        /*------------------------------------------------------------------
        If the form is Valid than this Sends the Form Data to the Controller
        ------------------------------------------------------------------*/
        await $.ajax({
            type: "POST",
            url: '/signup',
            data: {
                firstname: signupForm.querySelector("input[name=firstname]").value,
                surname: signupForm.querySelector("input[name=surname]").value,
                email: signupForm.querySelector("input[name=email]").value,
                password: signupForm.querySelector("input[name=password]").value,
                gender: gender
            },
            success: function (response) {
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        /*---------------------------------------------------------------------------
        The below statement is to avoid the duplicate username in case user resubmits
        the same form by comming back to signup form from home screen.
        ---------------------------------------------------------------------------*/
        signupForm.querySelector("input[name=email]").value = "";

        window.location = '/home';
    }
})


/* =====================================================
This Event Listener Validates the Form on each Key Press
===================================================== */
signupForm.addEventListener("keyup", async (e) => {
    /*---------------------------------------
    This check Validates the Confirm Password
    ---------------------------------------*/
    if (e.target.closest("input[name=cpassword]")) {
        if (
            signupForm.querySelector("input[name=password]").value
            !==
            e.target.closest("input[name=cpassword]").value
        ) {
            e.target.closest("input[name=cpassword]").style.border = defaulterBorder;
            e.target.closest("input[name=cpassword]").previousElementSibling.innerText = "Must be Equal To Password";
            passwordVerified = false;
        }
        else {
            e.target.closest("input[name=cpassword]").style.border = normalBorder;
            e.target.closest("input[name=cpassword]").previousElementSibling.innerText = "";
            passwordVerified = true;
        }
    }
    /*----------------------------
    This check Validates the Email
    ----------------------------*/
    else if (e.target.closest("input[type=email]")) {
        await $.ajax({
            type: "POST",
            url: '/verify-email',
            data: {
                email: e.target.closest("input[type=email]").value
            },
            success: function (response) {
                emailAlreadyExist = response;
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (emailAlreadyExist) {
            e.target.closest(".text-box").previousElementSibling.innerText = "Username is Already Taken";
            e.target.closest(".text-box").style.border = defaulterBorder;
        }
        else if (e.target.closest("input[type=email]").value !== "") {
            e.target.closest(".text-box").previousElementSibling.innerText = "";
            e.target.closest(".text-box").style.border = normalBorder;
        }
    }
    /*------------------------------------------------------------------------
    This check Validates the Text Boxes by checking that they are Empty or Not
    ------------------------------------------------------------------------*/
    else if (e.target.closest(".text-box")) {
        if (e.target.closest(".text-box").value !== "") {
            e.target.closest(".text-box").style.border = normalBorder;
            e.target.closest(".text-box").previousElementSibling.innerText = "";
        }
    }
})


/* ================================================
This Event Listener Validates the Form on Focus Out
================================================ */
signupForm.addEventListener("focusout", async (e) => {
    if (e.target.closest(".text-box")) {
        if (e.target.closest(".text-box").value == "") {
            e.target.closest(".text-box").style.border = defaulterBorder;
            e.target.closest(".text-box").previousElementSibling.innerText = "Cannot Be Empty";
        }
    }
})
