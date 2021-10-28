$(document).ready(function () {

    $("#message").emojioneArea({
        pickerPosition : "top",
        tonesStyle     : "bullet"
    })

    /**
     * Hide first before selecting a chat friend list
     */
    $(".card-footer").hide();

    /**
     * Sending images files
     * 
     */
    $("#file_image").on('change',function()
    {
        if($(this)[0].files.length > 0)
        {
            var photo = $(this)[0].files[0];
        }
        else 
        {
            var photo = "";
        }

        var fd          = new FormData();
        var friendsID   = $('.friends').attr('id');
        
        fd.append('photo',photo);
        fd.append('friendsID',friendsID);

        $.ajax({
            url         : 'home/uploadImage',
            type        : 'post',
            dataType    : 'json',
            data        : fd,
            contentType : false,
            processData : false,
            success     : function(xhr)
            {
                if(xhr.status)
                {
                    $(this).val('');
                }
                else 
                {
                    swal.fire({
                        icon  : 'error',
                        title : 'Failed',
                        text  : xhr.message
                    });
                }
            }
        })
    });


    /**
     * This is for the contact list section on the left
     *
     */
    var x  = setInterval(function()
    {   

        var id = $(".friends").attr('id');

        if(id != '')
        {   
            var data = { id : id };

            /**
             * This is useful for displaying the chat only.
             * Retrieve chat data every second using ajax
             * 
             */
            $.ajax({
                url     : 'home/showChat',
                data    : data,
                type    : 'post',
                dataType: 'json',
                success : function(xhr)
                {
                    if(xhr.status)
                    {
                        $(".msg_card_body").html(xhr.content);
                    }
                    else 
                    {
                        $(".msg_card_body").html("");
                    }
                }  
            });
        }

        /**
         * Show contact chat list every second with ajax
         * 
         */
        var id = "";
        var data   = { id : btoa(id)};
        var search = $(".search").val();
        if(search.length == 0)
        {
            $.ajax({
                url      : 'home/getDataListContact',
                type     : 'post',
                data     : data,
                dataType : 'json',
                success  : function(xhr)
                {
                    if(xhr.status)
                    {
                        $(".body-list-contact").html(JSON.parse(JSON.stringify(xhr.data)));
                    }
                }
                
            })
        }


    },3000);
});

/**
 * When you click enter in the chat input
 * 
 */
$("#type_message").on('keydown',function(e)
{
    if(e.which == 13)
    {
        var typing = $(this).val();
        
        if(typing.length > 0)
        {
            var friendsID = $(".friends").attr('id'); 
            
            var data    = 
            {
                typing    : btoa(typing),
                contactID : friendsID
            }
            
            $.ajax({
                url     : 'home/sendChat',
                type    : 'post',
                data    : data,
                dataType: 'json',
                success : function(xhr)
                {
                    $("#type_message").val('');

                    if(xhr.status)
                    {
                        $(".msg_card_body").append(xhr.content);

                        /**
                         * When you are no longer typing then delete the words 'is typing'
                         * 
                         */
                        $.ajax({ url : 'home/deleteTyping', type : 'post' });
                        
                    }                    
                }
            })
        }
    }
});   

/**
 * This is for the top left profile section
 * 
 */
$('#action_profile_btn').click(function()
{
    $('.action_contact').toggle();
});

$("#action_menu_btn").click(function()
{
    $('.action_menu').toggle();
});


$("#logout").on('click',function()
{

    // $('.action_menu').css('display','none');

    var id = $(this).attr('data-id');

    swal.fire({
        title : 'Are you sure',
        text  : 'You will end this session',
        icon  : 'question',
        showCancelButton : true,
        confirmButtonColor : '#3085d56',
        cancelButtonColor : '#d33',
        confirmButtonText : 'Yes'
    }).then((result) => {
        if(result.isConfirmed) 
        {
            $.ajax({ 
                url      : 'home/logout',
                type     : 'post', 
                dataType : 'json',
                data     : { id : id},
                success  : function(xhr)
                {
                    window.location.href = xhr.url;
                } 
            })
        }
    });
});

/**
 * It is used to retrieve status data when status is selected
 * 
 * Online | Offline | Outside | Busy
 */
$('body').on('click','.select_status',function() 
{
    var status = $(this).attr('id');

    $.ajax({
        url      : 'home/status',
        type     : 'post',
        data     : { status : status },
        dataType : 'json',
        success  : function(xhr)
        {
            if(xhr.status)
            {
                var statusName = "";

                switch(xhr.data)
                {
                    case 'online'  : 
                        statusName = 'online';
                    break;
                    case 'offline' : 
                        statusName = 'offline';
                    break;
                    case 'outside' : 
                        statusName = 'outside';
                    break;
                    case 'busy'    : 
                        statusName = 'busy';
                    break;
                }
                
                $("#profile_status").attr('class',xhr.data + '_icon');
                $("#status_name").text(statusName);

                $(".action_contact").css('display','none');
            }
        }

    })

});

/**
 * This is used to retrieve form data when the profile button is clicked
 * 
 */
$("#profile").on('click',function()
{
    // $('.action_menu').css('display','none');

    var id = $(this).data('id');

    $.ajax({
        url      : 'home/getDataProfile',
        type     : 'post',
        dataType : 'json',
        data     : { id : id },
        success  : function(xhr)
        {
            if(xhr.status)
            {
                $("#username").val(xhr.data.username);
                $("#fullname").val(xhr.data.fullname);
                $("#prev_photo").val(xhr.data.photo);
                $("#form_edit_profile").attr('data-id',id);
            }
        }
    })
});

/**
 * Process of updating profile data 
 * 
 */
function updateProfile(e)
{
    if($("#photo")[0].files.length > 0)
    {
        var photo = $("#photo")[0].files[0];
    }
    else 
    {
        var photo = "";
    }
    
    var username    = $("#username").val();
    var fullname    = $("#fullname").val();
    var password    = btoa($("#password").val());
    var id          = $("#form_edit_profile").data('id');
    var prevPhoto   = $("#prev_photo").val();
    var message     = "";
    var formData    = new FormData();
    var error       = 0;

    /* Check if the full name is not empty */
    if(fullname === "")
    {
        message = "Fullname is required";
        validation(message,'#fullname_error');
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
    
    if(error == 0)
    {
        formData.append('username',username);
        formData.append('fullname',fullname);
        formData.append('password',password);
        formData.append('id',id);
        formData.append('photo',photo);
        formData.append('prevPhoto',prevPhoto);

        $.ajax({
            url      : 'home/check_username_update',
            type     : 'post',
            dataType : 'json',
            data     : { username : username },
            success  : function(xhr)
            {
                if(xhr.status)
                {
                    validation(xhr.message,'#username_error');
                    return 0;
                }
                else 
                {
                    $.ajax({
                        url         : 'home/updateProfile',
                        type        : 'post',
                        data        : formData,
                        dataType    : 'json',
                        processData : false,
                        contentType : false,
                        success     : function(xhr)
                        {
                            if(xhr.status)
                            {
                                swal.fire({
                                    icon     : 'success',
                                    title    : 'Success',
                                    text     : xhr.message,
                                    showConfirmButton : true,
                                }).then((result) => {
                                    if(result.isConfirmed)
                                    {
                                        window.location.reload();
                                    }
                                });
                                
                            }
                            else 
                            {
                                swal.fire({
                                    icon  : 'error',
                                    title : 'Failed',
                                    text  : xhr.message,
                                    showConfirmButton : true
                                }).then((result) => {
                                    if(result.isConfirmed)
                                    {
                                        window.location.reload();
                                    }
                                });
                            }
                        }
                    })
                }
            }
        });

    
    
    }

    e.preventDefault();

}

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
 * When the contact list is clicked or selected
 * 
 */
$('body').on('click','.list-contact',function(e)
{
    $(".card-footer").show();

    var id = $(this).attr('id');

    var data = { id : id };
    
    /**
     * This is useful for displaying the chat only
     * 
     */
    $.ajax({
        url     : 'home/showChat',
        data    : data,
        type    : 'post',
        dataType: 'json',
        success : function(xhr)
        {
            if(xhr.status)
            {
                $(".msg_card_body").html(xhr.content);
            }
            else 
            {
                $(".msg_card_body").html("");
            }
        }  
    });

    /**
     * To display profile photos and chat friends icon status
     * 
     */
    $.ajax({
        url     : 'home/profileFriends',
        data    : data,
        type    : 'post',
        dataType: 'json',
        success : function(xhr)
        {
            if(xhr.status)
            {
                $(".msg_head").html(xhr.content);
            }
        }
    })
});

/**
 * When searching for a chat list contact list in the search input
 * 
 */
$('body').on('keyup','.search',function()
{
    var search = $('.search').val();

    if(search.length > 0)
    {
        var data = { search : btoa(search) };

        $.ajax({
            url      : 'home/getDataListContactSearch',
            type     : 'post',
            data     : data,
            dataType : 'json',
            success  : function(xhr)
            {
                if(xhr.status)
                {
                    $(".body-list-contact").html(JSON.parse(JSON.stringify(xhr.data)));
                }
            }
            
        })
    }
});

/**
 * Give the status of typing to chat friends
 * 
 */
$('body').on('keyup','#type_message',function()
{
    var typing = $('#type_message').val();

    if(typing.length > 0)
    {
        var friendsID = $(".friends").attr('id');
        
        $.ajax({ url : 'home/statusTyping',data : { friendsID : friendsID } , type : 'post' });
    }
    else 
    {
        /**
         * When you are no longer typing then delete the words 'is typing'
         * 
         */
        $.ajax({ url : 'home/deleteTyping', type : 'post' });
    }
});