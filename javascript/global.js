
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
                    console.log(event.target.files);
                    // Also here use the following to remove only the deleted image: $(this).parent().remove();
                    $(this).parent().parent().find(".post-creation-item").remove();
                });
            };
        
            reader.readAsDataURL(selectedFile);
        }
    }
    xmlhttprequest.open("GET", "api/general/createpost/create_post_data_item.php", true);
    xmlhttprequest.send();
});

$(".share-post").click(function(event) {
    event.preventDefault();

    // ADD POST THROUGH AJAX
    console.log("post");
})
