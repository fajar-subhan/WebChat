<div class="limiter">
    <div class="container-login100" style="background-image: url('assets/images/bg-01.jpg');">
        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
            <form class="login100-form validate-form" id="form_login">
                <span class="login100-form-title p-b-49">
                    Login
                </span>

                <div class="wrap-input100 m-b-23" id="username_error" data-validate="">
                    <span class="label-input100">Username</span>
                    <input class="input100" type="text" id="username" name="username" value="<?php echo cek_cookie('username') ? Decrypt(get_cookie('username')) : '';  ?>" placeholder="Type your username">
                    <span class="focus-input100" data-symbol="&#xf206;"></span>
                </div>

                <div class="wrap-input100 m-b-23" id="password_error" data-validate="">
                    <span class="label-input100">Password</span>
                    <input class="input100" type="password" id="password" name="password" value="<?php echo cek_cookie('password') ? get_cookie('password') : ''; ?>" placeholder="Type your password">
                    <span class="focus-input100" data-symbol="&#xf190;"></span>
                </div>

                <div class="text-right p-t-8 p-b-31">
                    <span class="forgot_password" style="font-family: Poppins-Regular;font-size : 14px;line-height:1.7;color:#666666;margin:0px;padding-right:34%;">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">
                            Remember me?
                        </label>
                    </span>

                    <a href="<?php echo BASE_URL ?>auth/forgot">
                        Forgot password?
                    </a>
                </div>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>
                </div>

                <div class="txt1 text-center p-t-54 p-b-20">
                    <span>
                        Or Sign Up Using
                    </span>
                </div>

                <div class="flex-col-c">
                    <a href="<?php echo BASE_URL ?>auth/register" class="txt2">
                        Sign Up
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="dropDownSelect1"></div>