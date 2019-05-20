/*
Abstract : Ajax Page Js File
File : dz.ajax.js
#CSS attributes: 
	.dzForm : Form class for ajax submission. 
	.dzFormMsg  : Div Class| Show Form validation error/success message on ajax form submission

#Javascript Variable
.dzRes : ajax request result variable
.dzFormAction : Form action variable
.dzFormData : Form serialize data variable

*/
$(function() {

    var msgDiv;

    $("#dzForm").on("submit", function(e)
    {

        if (!e.isDefaultPrevented()) {


            $('.dzFormMsg').html('<div class="gen alert alert-success">Submitting..</div>');
            var dzFormAction = $(this).attr('action');
            var dzFormData = $(this).serialize();

            $.ajax({
                method: "POST",
                url: dzFormAction,
                data: dzFormData,
                dataType: 'json',
                success: function(dzRes){
                    if(dzRes.status == 1){
                        msgDiv = '<div class="gen alert alert-success">'+dzRes.msg+'</div>';
                    }

                    if(dzRes.status == 0){
                        msgDiv = '<div class="err alert alert-danger">'+dzRes.msg+'</div>';
                    }
                    $('.dzFormMsg').html(msgDiv);
                    $('.dzForm')[0].reset();

                }
            })

            return false;
        }
       
    });
    setInterval(function(){
        $('.dzFormMsg .alert').hide(1000);
    }, 10000);
});