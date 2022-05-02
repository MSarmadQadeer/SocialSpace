const loginForm = document.querySelector(".login-form");
const loginBtn = document.querySelector(".login-btn");
const create_new_account_btn = document.querySelector(".create-account-btn");


create_new_account_btn.addEventListener("click", (e) => {
    e.preventDefault();
    window.location = '/signup';
})


const email = document.querySelector("input[name=email]");
const password = document.querySelector("input[name=password]");
const allTextBoxes = document.querySelectorAll(".text-box");


// =========================================
// Below are the borders for Form Validation
// =========================================
const normalBorder = "solid 1px rgb(233, 236, 255)";
const defaulterBorder = "solid 1px rgb(191, 0, 255)";

loginBtn.addEventListener("click", async (e) => {
    let valid = true;
    let validityResponse = 0;
    allTextBoxes.forEach((textBox) => {
        if (textBox.value == "") {
            textBox.style.border = defaulterBorder;
            textBox.previousElementSibling.innerText = "Cannot Be Empty";
            valid = false;
        }
    })

    if (valid) {
        /*------------------------------------------------------------------
        If the form is Valid than this Sends the Form Data to the Controller
        and in return receives the result about user validity.
        ------------------------------------------------------------------*/
        await $.ajax({
            type: "POST",
            url: '/login',
            data: {
                email: email.value,
                password: password.value
            },
            success: function (response) {
                validityResponse = response;
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 1 ---> When the Email is Incorrect
        if (validityResponse == 1) {
            email.value = "";
            email.style.border = defaulterBorder;
            email.previousElementSibling.innerText = "Invalid Email";
            password.value = "";
            password.style.border = defaulterBorder;
            password.previousElementSibling.innerText = "Invalid Password";
        }
        // 2 ---> When the Password is Incorrect
        else if (validityResponse == 2) {
            password.value = "";
            password.style.border = defaulterBorder;
            password.previousElementSibling.innerText = "Invalid Password";
        }
        // 3 ---> Good To Go
        else if (validityResponse == 3) window.location = '/home';
    }
})


loginForm.addEventListener("keyup", (e) => {
    if (e.target.closest(".text-box")) {
        if (e.target.closest(".text-box").value !== "") {
            e.target.closest(".text-box").style.border = normalBorder;
            e.target.closest(".text-box").previousElementSibling.innerText = "";
        }
    }
})



loginForm.addEventListener("focusout", async (e) => {
    if (e.target.closest(".text-box")) {
        if (e.target.closest(".text-box").value == "") {
            e.target.closest(".text-box").style.border = defaulterBorder;
            e.target.closest(".text-box").previousElementSibling.innerText = "Cannot Be Empty";
        }
    }
})
