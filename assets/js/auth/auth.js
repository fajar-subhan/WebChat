$(document).ready(function()
{
    /**
     * Login Proses 
     * 
     * This function is used to check the account logged into the application.
     * From validation to login process using ajax
     */
    $("#form_login").on("submit",function(e)
    {
        e.preventDefault();
    });

    /**
     * Registration process
     * 
     * This function is used to register chat member accounts
     */
    $("#form_register").on("submit",function(e)
    {
        var fullname = $("#fullname").val().trim();
        var username = $("#username").val().trim();
        var password = $("#password").val().trim();
        var formData = $(this).serialize();
        var message  = "";
        var error    = 0;

        /* Check if the full name is not empty */
        if(fullname === "")
        {
            message = "Fullname is required";
            validation(message,'#fullname_error');
            error++;
        }
        /* Full name is only filled in upper and lower case letters, may not use numbers  */
        else if(/^[A-za-z ]{1,}$/.test(fullname) === false)
        {
            message = "Fullname only upper and lower case letters";
            validation(message,'#fullname_error');
            error++;
        }  
        /* Minimum character length 3 characters maximum 50 characters */
        else if((fullname.length < 3) || (fullname.length > 50))
        {
            message = "Fullname minimum 3 characters and maximum 50 characters";
            validation(message,'#fullname_error');
            error++;
        }

        /* Check if the user name is not empty */
        if(username === "")
        {
            message = "Username is required";
            validation(message,'#username_error');
            error++;
        }
        /* Minimum character length 3 characters maximum 50 characters */
        else if((username.length < 3) || (username.length > 50))
        {
            message = "Username minimum 3 characters and maximum 50 characters";
            validation(message,'#username_error');
            error++;
        }
        /* Check if the username is already used by another user */
        else 
        {
            $.ajax({
                url      : 'check_username',
                type     : 'post',
                dataType : 'json',
                data     : { username : username },
                success  : function(xhr)
                {
                    if(xhr.status)
                    {
                        swal.fire({
                            icon  : 'info',
                            title : 'Failed',
                            text  : xhr.message,
                        });

                        error++;
                    }
                }
            });
        }

        /* Check if the password is not empty */
        if(password === "")
        {
            message = "Password is required";
            validation(message,'#password_error');
            error++;
        }
        /* Minimum character length 6 characters maximum 20 characters */
        else if((password.length < 6) || (password.length > 20))
        {
            message = "Password minimum 6 characters and maximum 20 characters";
            validation(message,'#password_error');
            error++;
        }

        /**
         * If there is no error then run ajax 
         * to save the registered member data
         * 
         */
        if(error == 0)
        {
            $.ajax({
                url      : 'add_account',
                type     : 'post',
                dataType : 'json',
                data     : formData,
                success  : function(xhr)
                {
                    if(xhr.status)
                    {
                        swal.fire({
                            icon  : 'success',
                            title : 'Success',
                            text  : xhr.message,
                        });

                        $('#form_register')[0].reset();
                    }
                    else 
                    {
                        swal.fire({
                            icon  : 'error',
                            title : 'Failed',
                            text  : xhr.message,
                        });

                        $('#form_register')[0].reset();
                    }
                }
            })
        }

        e.preventDefault();
    })
        

    /**
     * Perform the validation process and check whether there is an error or not.
     * If there is an error, display an error
     * 
     * @param {string} message 
     * @param {string} selectorerror 
     */
    function validation(message,selectorerror)
    {
        if(message !== "" && message !== undefined)
        {
            $(selectorerror).attr('data-validate',message);
            $(selectorerror).addClass('validate-input alert-validate');
        }
        else 
        {
            $(selectorerror).attr('data-validate','');
            $(selectorerror).removeClass('validate-input alert-validate');
        }
    }

    /**
     * Forgot password process 
     * 
     * Function to forgot password, 
     * in order to update password
     */
    $("#form_forgot").on('submit',function(e)
    {
        var formData = $(this).serialize();
        var username = $("#username").val().trim();
        var password = $("#password").val().trim();
        var message  = "";
        var error    = 0;

        /* Check if the user name is not empty */
        if(username === "")
        {
            message = "Username is required";
            validation(message,'#username_error');
            error++;
        }
        /* Minimum character length 3 characters maximum 50 characters */
        else if((username.length < 3) || (username.length > 50))
        {
            message = "Username minimum 3 characters and maximum 50 characters";
            validation(message,'#username_error');
            error++;
        }

        /* Check if the password is not empty */
        if(password === "")
        {
            message = "Password is required";
            validation(message,'#password_error');
            error++;
        }
        /* Minimum character length 6 characters maximum 20 characters */
        else if((password.length < 6) || (password.length > 20))
        {
            message = "Password minimum 6 characters and maximum 20 characters";
            validation(message,'#password_error');
            error++;
        }

        if(error == 0)
        {
            $.ajax({
                url      : 'update_password',
                type     : 'post',
                dataType : 'json',
                data     : formData,
                success  : function(xhr)
                {
                    if(xhr.status)
                    {
                        swal.fire({
                            icon  : 'success',
                            title : 'Success',
                            text  : xhr.message,
                        });

                        $('#form_forgot')[0].reset();
                    }
                    else 
                    {
                        swal.fire({
                            icon  : 'error',
                            title : 'Failed',
                            text  : xhr.message,
                        });

                        $('#form_forgot')[0].reset();
                    }
                }
            })
        }
        
        e.preventDefault();
    });

});