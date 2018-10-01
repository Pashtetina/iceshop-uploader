$(document).ready(function(){
   uploadvialink();
    search();
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

                    $('.js-uploadmessage').text(JSON.stringify(result, undefined, 4 )).show();
            },
            type: 'post',
        });

        $('#uploadForm').ajaxForm({
            target: '#outputImage',
            url: 'uploadFile.php',
            beforeSubmit: function () {
                $("#outputImage").hide();
                if($("#uploadImage").val() == "") {
                    $("#outputImage").show();
                    $("#outputImage").html("<div class='error'>Choose a file to upload.</div>");
                    return false;
                }
                $("#progressDivId").css("display", "block");
                var percentValue = '0%';

                $('#progressBar').width(percentValue);
                $('#percent').html(percentValue);
            },
            uploadProgress: function (event, position, total, percentComplete) {

                var percentValue = percentComplete + '%';
                $("#progressBar").animate({
                    width: '' + percentValue + ''
                }, {
                    duration: 5000,
                    easing: "linear",
                    step: function (x) {
                        percentText = Math.round(x * 100 / percentComplete);
                        $("#percent").text(percentText + "%");
                        if(percentText == "100") {
                            $("#outputImage").show();
                        }
                    }
                });
            },
            error: function (response, status, e) {
                alert('Oops something went.');
            },

            complete: function (xhr) {
                if (xhr.responseText && xhr.responseText != "error")
                {
                    $("#outputImage").html(xhr.responseText);
                }
                else{
                    $("#outputImage").show();
                    $("#outputImage").html("<div class='error'>Problem in uploading file.</div>");
                    $("#progressBar").stop();
                }
            }
        });

    });

}

function search()
{
    $('.js-search').on('submit', function(e){
        var form = $(this);
        e.preventDefault();
        $.ajax({
            url: "",
            data: form.serialize(),
            success: function(result){

                $('.js-searchmessage').text(JSON.stringify(result, undefined, 4 )).show();
            },
            type: 'post',
        });
    });
}