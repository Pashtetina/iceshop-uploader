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
                $("#div1").html(result);
            },
            type: 'post',

        });
    });
}