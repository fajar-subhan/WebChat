<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password Chat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL ?>assets/images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>assets/css/main.css">
    <!--===============================================================================================-->
</head>

<body>

    <div class="limiter">
        <div class="container-login100" style="background-image: url('../assets/images/bg-01.jpg');">
            <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
                <form class="login100-form validate-form" id="form_forgot">
                    <span class="login100-form-title p-b-49">
                        Forgot Password
                    </span>

                    <div class="wrap-input100 m-b-23" id="username_error" data-validate="">
                        <span class="label-input100">Username</span>
                        <input class="input100" type="text" id="username" name="username" placeholder="Type your username">
                        <span class="focus-input100" data-symbol="&#xf206;"></span>
                    </div>

                    <div class="wrap-input100 m-b-23" id="password_error" data-validate="">
                        <span class="label-input100">Password</span>
                        <input class="input100" type="password" id="password" name="password" placeholder="Type your new password">
                        <span class="focus-input100" data-symbol="&#xf190;"></span>
                    </div>

                    <div class="text-right p-t-8 p-b-31">
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn" type="submit">
                                Update
                            </button>
                        </div>
                    </div>

                    <div class="text-right p-t-8 p-b-31">
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <a href="<?php echo BASE_URL ?>" class="login100-form-btn text-white">
                                Login
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    <!--===============================================================================================-->
    <script src="<?php echo BASE_URL ?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo BASE_URL ?>assets/vendor/bootstrap/js/popper.js"></script>
    <script src="<?php echo BASE_URL ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo BASE_URL ?>assets/js/auth/main.js"></script>
    <!--===============================================================================================-->
    <script src="<?php echo BASE_URL ?>assets/js/auth/auth.js"></script>
    <script src="<?php echo BASE_URL ?>assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
</body>

</html>