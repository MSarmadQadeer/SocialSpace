<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title")</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="d-md-flex">

        @include('components.product-logo')

        <div class="col-xl-7 col-md-6 col-12 d-flex align-items-center justify-content-center login-signup-form-panel">

            @yield("card")

        </div>
    </div>

    <script>
        const login_signup_form_panel = document.querySelector(".login-signup-form-panel");
        const product_logo_nav = document.querySelector(".product-logo-nav");

        function adjustFormPanelHeight() {
            if (window.innerWidth >= 768) {
                product_logo_nav.classList.add("hide");
            } else {
                product_logo_nav.classList.remove("hide");
            }
            login_signup_form_panel.style.height = `${window.innerHeight - product_logo_nav.offsetHeight}px`;
        }
        adjustFormPanelHeight();

        window.addEventListener("resize", adjustFormPanelHeight);
    </script>

    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>

</body>

</html>
