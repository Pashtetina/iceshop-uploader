$(document).ready(function(){
   uploadvialink();
});

function uploadvialink()
{
    $('.js-uploadbylink').on('submit', function(e){
        var form = $(this);
        e.preventDefault();
        $.ajax({
            url: "",
            data: form.serialize(),
            success: function(result){
                if(result.status == 'ok')
                {
                    $('.js-message').html('Success').show();
                }
            },
            type: 'post',
        });
    });
}