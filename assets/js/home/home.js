$(document).ready(function () {

    $("#message").emojioneArea({
        pickerPosition : "top",
        tonesStyle     : "bullet"
    })
    /**
     * This is for the contact list section on the left
     *
     */
    setInterval(function()
    {   
        var id = 2;
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
    },2000);
        
});

$('#action_menu_btn').click(function () {
    $('.action_menu').toggle();
});

/**
 * This is for the top left profile section
 * 
 */
$('#action_profile_btn').click(function()
{
    $('.action_contact').toggle();
});

$("#logout").on('click',function()
{
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
            }
        }

    })

});