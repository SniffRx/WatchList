$(document).on("click", ".vc_server_acc_name", (e) =>
{
    if( $(e.currentTarget).hasClass("not-open") )
        return;
    
    $(e.currentTarget).parent().find(".vc_server_acc_content").slideToggle(200);
    let icon = $(e.currentTarget).parent().find(".zmdi.arrow");
    ( icon.hasClass("zmdi-chevron-down") ) ? icon.removeClass("zmdi-chevron-down").addClass("zmdi-chevron-up") : icon.removeClass("zmdi-chevron-up").addClass("zmdi-chevron-down");
});

function SendAjax(id, func, da = "da") {
    $.ajax({
        type: "POST",
        url: location.href,
        data: $("#"+id).serialize() + "&func="+func + "&da="+da,
        success: function(response)
        {
            var jsonData = $.parseJSON(response);

            if (jsonData.data)
            {
                note({
                    content: jsonData.data,
                    type: jsonData.status,
                    time: 5
                });
                PlaySound(`storage/assets/sounds/${jsonData.status}.mp3`);
                (jsonData.status == "success" ) && setTimeout(( e ) => {window.location.reload();}, 2000);
                return;
            }
       }
   });
}
