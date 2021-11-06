<div class="container-fluid h-100">
    <div class="row justify-content-center h-100">
        <!-- <============== Start chat on the left ===================> -->
        <div class="col-md-4 col-xl-3 chat">
            <div class="card mb-sm-3 mb-md-0 contacts_card">
                <div class="card-header">
                    <!-- Contact Profile -->
                    <ul class="contacts">
                        <li>
                            <div class="d-flex bd-highlight">
                                <div id="action_profile_btn">
                                    <div class="img_cont">
                                        <img id="profile_img" src="<?php echo BASE_URL ?>assets/images/contacts/<?php echo Decrypt(GetProfile(userdata('id'))['photo']); ?>" class="rounded-circle user_img">

                                        <span class="<?php echo StatusIcon(GetProfile(userdata('id'))['online']) ?>" id="profile_status"></span>
                                    </div>
                                </div>

                                <div class="user_info">
                                    <span><?php echo  GetProfile(userdata('id'))['fullname']; ?></span>
                                    <p id="status_name"><?php echo strtolower(GetProfile(userdata('id'))['status_name']); ?></p>

                                    <div class="action_contact">
                                        <ul>
                                            <?php foreach (Status() as $key => $value) : ?>
                                                <li class="select_status" id="<?php echo Encrypt($key) ?>">
                                                    <a>
                                                        <span class="status status-<?php echo strtolower($value) ?>"></span>
                                                        <?php echo $value; ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>

                                    <span id="action_menu_btn"><i class="fa fa-ellipsis-v"></i></span>
                                    <div class="action_menu">
                                        <ul>
                                            <li id="profile" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="<?php echo Encrypt(userdata('id')) ?>">
                                                <i class="fa fa-user-circle"></i> Profile
                                            </li>
                                            <li id="logout" data-id="<?php echo Encrypt(userdata('id')) ?>">
                                                <i class="fa fa-sign-out"></i> Logout
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="input-group">
                        <input type="text" placeholder="Search by message" name="" class="form-control search">
                    </div>
                </div>
                <div class="card-body contacts_body">
                    <ul class="contacts">
                        <div class="body-list-contact">
                            
                        </div>
                    </ul>
                </div>

                <div class="card-footer"></div>
            </div>
        </div>
        <!-- <============== End chat on the left ===================> -->

        <!-- <============== Start chat on the right ===================> -->
        <div class="col-md-8 col-xl-6 chat">
            <div class="card">
                <div class="card-header msg_head">
                
                </div>
                <div class="card-body msg_card_body">

                </div>
                <div class="card-footer">
                    <div class="input-group">

                        <label for="file_image">
                            <span class="input-group-text file_span"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                            <input type="file" class="file" id="file_image" accept="image/*">
                        </label>

                        <label for="file_upload">
                            <span class="input-group-text file_span"><i class="fa fa-paperclip" aria-hidden="true"></i></span>
                            <input type="file" class="file" id="file_upload">
                        </label>
                        <textarea class="form-control type_msg"  id="type_message" placeholder="Type your message..."></textarea>
                        <div class="input-group-append">
                            <label for="file_send">
                                <span class="input-group-text file_span" id="file_send"><i class="fa fa-paper-plane" aria-hidden="true"></i></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <============== Start chat on the right ===================> -->
    </div>
</div>

<!-- Start modal profile -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-user-circle"></i> Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="limiter">
                    <div class="container-login100">
                        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
                            <form action="#" data-id="" class="login100-form validate-form" id="form_edit_profile" onsubmit="updateProfile(event)">
                                <input type="hidden" id="prev_photo" value="">

                                <div class="wrap-input100 m-b-23" id="fullname_error" data-validate="">
                                    <span class="label-input100">Fullname</span>
                                    <input class="input100" type="text" id="fullname" name="fullname" placeholder="Type your new fullname">
                                    <span class="focus-input100" data-symbol="&#xf206;"></span>
                                </div>

                                <div class="wrap-input100 m-b-23" id="username_error" data-validate="">
                                    <span class="label-input100">Username</span>
                                    <input class="input100" type="text" id="username" name="username" placeholder="Type your new username">
                                    <span class="focus-input100" data-symbol="&#xf206;"></span>
                                </div>

                                <div class="wrap-input100 m-b-23" id="photo_error" data-validate="">
                                    <span class="label-input100">Photo</span>
                                    <input type="file" name="photo" id="photo">
                                    <small class="text-muted" style="font-size:8px;font-weight:bold;">You can only upload in jpg, jpeg, png format with dimensions of 800x800 pixels</small>
                                </div>

                                <div class="wrap-input100 m-b-23" id="password_error" data-validate="">
                                    <span class="label-input100">Password</span>
                                    <input class="input100" type="password" id="password" name="password" placeholder="Type your message">
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
                            </form>
                        </div>
                    </div>
                </div>

                <div id="dropDownSelect1"></div>
            </div>
        </div>
    </div>
</div>