$(document).ready(function () {

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
    },6000);
        
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