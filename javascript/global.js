
$("#create-post-textual-content").on({
    keyup: function() {
        if($(this).val() != "") {
            $("#post-create-button").css("display", "block");
        } else {
            $("#post-create-button").css("display", "none");
        }
    }
});

$("#create-post-photo-or-video").change(function(event) {
    let xmlhttprequest = new XMLHttpRequest();
    xmlhttprequest.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            if(event.target.files != undefined) {
                $("#post-create-button").css("display", "block");
            } else {
                $("#post-create-button").css("display", "none");
            }

            /* Here first you need to check the incoming data and based on it, you can either decide to show the image or keep it none displayed
            in case it is a malicious file or not an appropriate image*/
            $(".image-post-uploaded-container").append(this.responseText);
            // We search for the last div added and go deep to the image to get the element
            let imgtag = $(".image-post-uploaded-container .post-creation-item").last().find(".image-post-uploaded");
            // FOR NOW LET'S FOCUS ON ONLY ONE IMAGE
            var selectedFile = event.target.files[0];
            var reader = new FileReader();
        
            reader.onload = function(e) {
                imgtag.attr("src", e.target.result);
                $(".delete-uploaded-item").click(function() {
                    // FileList in javascript is readonly So: for now let's botter our heads with only posting one image
                    //Here we need only to remove this image and not all the images in the queue
                    $("#create-post-photo-or-video").val("");
                    // Also here use the following to remove only the deleted image: $(this).parent().remove();
                    $(this).parent().parent().find(".post-creation-item").remove();
                    if($("#create-post-textual-content").val() != "") {
                        $("#post-create-button").css("display", "block");
                    } else {
                        $("#post-create-button").css("display", "none");
                    }
                });
            };
        
            reader.readAsDataURL(selectedFile);
        }
    }
    xmlhttprequest.open("GET", "api/general/createpost/create_post_data_item.php", true);
    xmlhttprequest.send();
});

$(".share-post").click(function(event) {
    /*
    I SPENT WITH THAT FEATURE More than 2 Hours in a row and I got a headache so please read the following statement:
    When share-post submit button get clicked we prevent the default behaviour of submitting data to the server
    So we need to get the posted data from the form
    IMPORTANT: when we prevent the default behaviour of submit button this button will not be submitted with the form
    and in the API we based our post task on this buttton so we need to APPEND THIS SUBMIT BUTTON TO THE FORM DATA with it's proper name used in the api (share-post)
                                                                        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
                                                                        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    Actually we don't have to :)
    In the api we don't have to check wether the submit button is set or not because we do it here and we only call the 
    api to add a post and because the api is RESTful it has no state or state of button to be depend on
    */

    event.preventDefault();

    $(".share-post").attr('disabled','disabled');
    $(".share-post").attr('value', "POSTING ..");

    let formData = new FormData($("#create-post-form").get(0));

    $.ajax({
        url: $("#create-post-form").attr('action'),
        method: 'POST',
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        data: formData,
        success: function(response){
            $(".share-post").removeAttr('disabled');
            $(".share-post").attr('value', "POST");

            $(".post-created-message").css("display", "block");
            $(".post-created-message").animate({
                    opacity: 1
            }, 300);
            setTimeout(function() { 
                $(".post-created-message").animate({
                    opacity: 0
                }, 300);
            }, 3000, function() {$(".post-created-message").css("display", "none");});
        },
        error: function(){
            console.log('error')
        }
    });
});