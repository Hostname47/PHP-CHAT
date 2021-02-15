$(".header-profile-edit-button").click(function() {
    window.location.href = root + "profile.php?edit";
    
    return false;
})

$(".delete-message-hint").click(function() {
    $(this).parent().css("display", "none")
})

$("#addd").click(function() {
    $.ajax({
        type: 'post',
        url: root + "view/post/generate_post.php",
        data: {
            "post_id": 40
        },
        success: function(component) {
            $("#posts-container").prepend(component);
        }
    })

    return false;
});

let headerHeight = 55;

function adjust_left_right_containers() {
    $("#master-right").height($(window).height() - headerHeight - 4);
    $("#master-left").height($(window).height() - headerHeight - 7);
    $("#master-left-container").height($("#master-left").height() - 84);
    $("#contacts-container").height($("#master-right").height() - 40);
}

adjust_left_right_containers();

$(window).resize(function() {
    adjust_left_right_containers();
})

$(".button-with-suboption").click(function() {
    let container = $(this).parent().find(".sub-options-container");
    if(container.css("display") == "none") {
        $(".sub-options-container").css("display", "none");
        container.css("display", "block");
    } else {
        container.css("display", "none");
    }
    return false;
});

/* When the user click somewhere in bofy section all absolute containers will disappear except if the section is a
header container itself */
/*$("body").click(function(evt) {
    $(".sub-options-container").css("display", "none");
});*/

document.addEventListener("click", function(event) {
    $(".sub-options-container").css("display", "none");
}, false);

// we prevent
let subContainers = document.querySelectorAll('.sub-options-container');
for(let i = 0;i<subContainers.length;i++) {
    subContainers[i].addEventListener("click", function(evt) {
        $(this).css("display", "block");
        evt.stopPropagation();
    }, false);
}

$(".post-to-option").click(function() {
    if(!$(this).find(".rad-opt").is(":disabled")) {
        $(this).parent().find("input[name='post-to']").prop("checked", false);
        $(this).find("input").prop("checked", true);
    }
});

$("#create-post-textual-content").on({
    keyup: function() {
        if($(this).val() != "") {
            $("#post-create-button").css("display", "block");
        } else {
            if($("#post-assets").val() == "") {
                $("#post-create-button").css("display", "none");
            }
        }
    }
});

function adjust_post_uploaded_assets_indexes() {
    let counter = 0;
    $(".post-creation-item").each(function() {
        $(this).find(".pciid").val(counter);
        counter++;
    });
}

function validate_image_file_Type(files){
    let result = [];
    for(let i = 0; i<files.length;i++) {
        fileName = files[i].name;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile=="jpg" || extFile=="jpeg" || extFile=="png" || extFile=="gif"){
            result.push(files[i]);
        }
    }

    return result;
}

function validate_video_file_Type(files) {
    let result = [];
    for(let i = 0; i<files.length;i++) {
        fileName = files[i].name;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile=="mp3" || extFile=="webm" || extFile=="mpg" 
        || extFile=="mp2"|| extFile=="mpeg"|| extFile=="mpe" 
        || extFile=="mpv"|| extFile=="ogg"|| extFile=="mp4" 
        || extFile=="m4p"|| extFile=="m4v"|| extFile=="avi"){
            result.push(files[i]);
        }
    }

    return result;
}

let uploaded_post_assets = [];
let upa_counter = 0;
let cp_index = 0;
// This will track image uploads --- [Now it is possible to share more than one image] ---
$("#post-assets").change(function(event) {
    /* 
        IMPORTANT: Because this is input file, if it gets clicked two times a row, then it will remove all the first files and
        replace them with the new files so we will handle the situation where we upload more than one file; then we put them in an array;
        then later if the user want to add more image or video; we'll take that addition and append it to the array(uploaded_post_assets)
        First we get the container and store it in a variable, then we loop through files and assign each one to the container and append
        it to the post container to show it to the user
    */

    // First get the new uploaded files and append them to our new array uploaded_post_assets
    let files = event.originalEvent.target.files;
    if(files.length != validate_image_file_Type(files).length) {
        $(".red-message").css("display", "flex");
        $(".red-message-text").text("Some files have invalid format: Only JPG/PNG/JPEG and GIF files formats are supported.");
    }
    files = validate_image_file_Type(files);
    uploaded_post_assets.push(...files);
    // Then get the component skeleton
    $.ajax({
        type: 'GET',
        url: root + "view/post/generate_post_creation_image.php",
        success: function(response) {

            let container = response;

            // We check if there's no file and text area is empty we hide the share button
            if(files.length == 0 && $("#create-post-textual-content").val() == "") {
                $("#post-create-button").css("display", "none");
            } else {
                $("#post-create-button").css("display", "block");
            }

            // Now we loop through the new files and append components to post component as small images to show them to user
            for (let i = 0; i < files.length; i++) {
                /*
                    Here first you need to check the incoming data and based on it, you can either decide to show the image or keep it none displayed
                    in case it is a malicious file or not an appropriate image
                */
                $(".post-assets-uploaded-container").append(container);
                // We search for the last div added and go deep to the image to get the element
                let imgtag = $(".post-assets-uploaded-container .post-creation-item").last().find(".image-post-uploaded");

                var selectedFile = files[i];
                var reader = new FileReader();
            
                reader.onload = function(e) {
                    imgtag.attr("src", e.target.result);
                    // Here we adjust the image in center and choose height if width is greather and width if height is greather
                    if(imgtag.height() >= imgtag.width()) {
                        imgtag.width("100%");
                    } else {
                        imgtag.height("100%");
                    }
                    
                    // Here we call this function to adjust indexes
                    adjust_post_uploaded_assets_indexes();
                    if(upa_counter == 0) {

                        $(".delete-uploaded-item").click(function() {
                            // FileList in javascript is readonly So: for now let's botter our heads with only posting one image
                            // It's time to botter your fuckin' head with multiple images HHH Lol
                            //Here we need only to remove this image and not all the images in the queue

                            adjust_post_uploaded_assets_indexes();
                            // Here we want to get the index of item the user want to delete and loop through the array and
                            // Delete the item which has pciid input value with that index and
                            let delete_index = $(this).parent().find(".pciid").val();
                            console.log("delete : " + delete_index);
                            let new_arr = [];
                            let cn = 0;
                            for(let k=0; k<uploaded_post_assets.length; k++) {
                                if(k != delete_index) {
                                    new_arr[cn] = uploaded_post_assets[k];
                                    cn++;
                                }
                            }

                            // We remove it's component
                            $(this).parent().remove();



                            // If we remove all the items, then the length will be 0 and we have to hide the share post button
                            if($(".post-creation-item").length == 0 && $("#create-post-textual-content").val() == '') {
                                $("#post-create-button").css("display", "none");
                            }

                            //we assign the new array which has deleted item removed to uploaded_post_assets array
                            uploaded_post_assets = new_arr;
                        });

                        upa_counter++;
                    }
                };
            
                reader.readAsDataURL(selectedFile);
            }

            upa_counter = 0;
        }
    });
})

let uploaded_post_assets_videos = [];
$("#post-video").change(function(event) {
    console.log("upload a videos !");
    let files = event.originalEvent.target.files;
    if(files.length != validate_video_file_Type(files).length) {
        $(".red-message").css("display", "flex");
        $(".red-message-text").text("Some files have invalid format: Only .mp4,.webm,.mpg,.mp2,.mpeg,.mpe,.mpv,.ogg,.mp4,.m4p,.m4v,.avi file formats are supported.");
        return false;
    }
    files = validate_video_file_Type(files);
    uploaded_post_assets_videos.push(...files);
    // Then get the component skeleton

    if(uploaded_post_assets_videos.length == 0) {
        document.getElementById("post-video").value = "";
        return false;
    }
    
    console.log("I passed !");

    $.ajax({
        type: 'GET',
        url: root + "view/post/generate_post_creation_video.php",
        success: function(response) {

            let container = response;

            // We check if there's no file and text area is empty we hide the share button
            if(files.length == 0 && $("#create-post-textual-content").val() == "") {
                $("#post-create-button").css("display", "none");
            } else {
                $("#post-create-button").css("display", "block");
            }

            $(".post-assets-uploaded-container").append(container);

            let component = $(".post-assets-uploaded-container .post-creation-item").last();
            let vidtag = component.find(".video-post-thumbnail");

            var selectedFile = files[0];
            var reader = new FileReader();
            vidtag.parent().find(".assets-pending").css("display", "flex");

            reader.readAsDataURL(selectedFile);
            reader.onload = function(e) {
                vidtag.parent().find(".assets-pending").css("display", "none");
                vidtag.parent().find(".post-creation-video-image-container").css("display", "flex");
                
                let thumbnail = "";
                try {
                    // get the frame at 1.5 seconds of the video file
                    thumbnail = get_thumbnail(selectedFile, 1.5, component);
                } catch (ex) {
                    console.log("ERROR: ", ex);
                }

                // Here we adjust the image in center and choose height if width is greather and width if height is greather
                if(vidtag.height() >= vidtag.width()) {
                    vidtag.width("100%");
                } else {
                    vidtag.height("100%");
                }
                
                // Here we call this function to adjust indexes
                adjust_post_uploaded_assets_indexes();
                $(".delete-uploaded-item").click(function() {
                    // FileList in javascript is readonly So: for now let's botter our heads with only posting one image
                    // It's time to botter your fuckin' head with multiple images HHH Lol
                    //Here we need only to remove this image and not all the images in the queue

                    adjust_post_uploaded_assets_indexes();
                    // Here we want to get the index of item the user want to delete and loop through the array and
                    // Delete the item which has pciid input value with that index and
                    let delete_index = $(this).parent().find(".pciid").val();
                    console.log("delete : " + delete_index);
                    let new_arr = [];
                    let cn = 0;
                    for(let k=0; k<uploaded_post_assets.length; k++) {
                        if(k != delete_index) {
                            new_arr[cn] = uploaded_post_assets[k];
                            cn++;
                        }
                    }

                    // We remove it's component
                    $(this).parent().remove();



                    // If we remove all the items, then the length will be 0 and we have to hide the share post button
                    if($(".post-creation-item").length == 0 && $("#create-post-textual-content").val() == '') {
                        $("#post-create-button").css("display", "none");
                    }

                    //we assign the new array which has deleted item removed to uploaded_post_assets array
                    uploaded_post_assets = new_arr;
                });
            };
        }
    });
})

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

    let value = $("#create-post-textual-content").val().replace(/\n/g, '<br/>');
    $("#create-post-textual-content").val(value);
    console.log($("#create-post-textual-content").html());

    let formData = new FormData($("#create-post-form").get(0));
    
    // Append image files
    for(let i = 0;i<uploaded_post_assets.length;i++) {
        formData.append(uploaded_post_assets[i].name, uploaded_post_assets[i]);
    }

    // Append video files
    for(let i = 0;i<uploaded_post_assets_videos.length;i++) {
        formData.append(uploaded_post_assets_videos[i].name, uploaded_post_assets_videos[i]);
    }



    $.ajax({
        url: root + "api/post/post.php",
        method: 'POST',
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        data: formData,
        success: function(response){
            $(".share-post").removeAttr('disabled');
            $(".share-post").attr('value', "POST");

            // Clear text
            $("#create-post-textual-content").val("");
            // Remove image template components
            $(".post-assets-uploaded-container").find(".post-creation-item").remove();
            // Clear file
            $("#post-assets").val("");

            $.ajax({
                type: 'post',
                url: root + "view/post/generate_last_post.php",
                success: function(component) {
                    $("#posts-container").prepend(component);
                    handle_post_assets($(".post-item").first());
                }
            })

            /*
            IMPORTANT: WHEN token is generated along with the form, we push it to the session server superglobal, But when we
            use this token in the api we use it with Token::check function which check it and delete it when it uses it so we need
            some way to regenrate the token again and assignn it to the token_post as well as to session superglobal so that the 
            user could post 2 posts in the same page without refreshing the page to regenerate the token again
            Aim: when the post created we call other php file through AJAX and generate other token and store it into session and 
            assign it to token_post via javascript
            */

            $(".post-created-message").css("display", "block");
            $(".post-created-message").animate({
                    opacity: 1
            }, 300);
            setTimeout(function() { 
                $(".post-created-message").animate({
                    opacity: 0
                }, 300);
            }, 3000, function() {$(".post-created-message").css("display", "none");});
            $(".share-post").css('display', "none");
        },
        error: function(){
            console.log('error');
        }
    });
});

/*
    REMEMBER: Any relational operation between users need a button with appropriate class like add-user or unfriend and 
    along with that button you should provide two important inputs: current_user_id which point to the user that is currently
    logged in, and current_profile_id which point to the target user.
    The action button and the two fields need to be inside a form regardless on their DOM depth
*/

/*
    IMPORTANT: what happens when user click follow button ?
    -> First we check if the user who click the button is the same as the user who is currently logged in by sending the user_id
       to the check file within 'functions/security/check_current_user.php' to check the user, Then we want to make follow
       add as the default behaviour of follow button, If the user who clicks follow is not following the followed user
       we add the follow record and the user follow him successfully. In the other hand if the user is already follow
       him we know that from the response of add api file by checking success array element if the success is return false
       meaning iether the id is not valide or there's already a record with these 2 ids meaning the current user is already
       follow this guy
       In case of failure we asume that there's already follow between the two, so we call delete api file to delete the record
       Note: Notice when a user unfollow another user, that doesn't mean the later one unfollow the first one because the 
       following record only describe one way follow (if A follows B and B follows A and later B unfollow A; A still follows B)
*/

$(".follow-button").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let followButton = $(this);
    let form = $(this).parent();

    /*
        We do that because some inputs are not directly child of the form so when followButton get clicked we need to start from this button
        to the first form ancestor and use it as form because in friend sub-option we have an inut inside div which is not a direct
        child of follow-form
    */
    while(form.prop("tagName") != "FORM") {
        form = form.parent();
    }

    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add follow record (This basically add some layer of security)
                url = root + "api/follow/add.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["success"]) {
                            followButton.removeClass("follow-user");
                            followButton.attr("value", "Unfollow");

                            if($(".follow-label")) {
                                $(".follow-label").text("Unfollow");
                            }

                            if($(".follow-menu-header-form").find(".follow-button")) {
                                $(".follow-menu-header-form").find(".follow-button").removeClass("follow-user");
                                $(".follow-menu-header-form").find(".follow-button").addClass("followed-user");
                                $(".follow-menu-header-form").find(".follow-button").attr("value", "Followed");
                            }
                        } else {
                            url = root + "api/follow/delete.php";
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: form.serialize(),
                                success: function() {
                                    followButton.removeClass("followed-user");
                                    followButton.attr("value", "Follow");

                                    if($(".follow-label")) {
                                        $(".follow-label").text("Follow");
                                    }

                                    if($(".follow-menu-header-form").find(".follow-button")) {
                                        $(".follow-menu-header-form").find(".follow-button").removeClass("followed-user");
                                        $(".follow-menu-header-form").find(".follow-button").addClass("follow-user");
                                        $(".follow-menu-header-form").find(".follow-button").attr("value", "Follow");
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
});

$(".add-user").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let addButton = $(this);
    let form = $(this).parent();
    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add user relation record (This basically add some layer of security)
                url = root + "api/user_relation/send_request.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["error"]) {
                            // Here add code to display error div to user or something
                        }
                        else if(response["success"]) {
                            addButton.attr("value", "Cancel Request");
                        } else {
                            url = root + "api/user_relation/cancel_request.php";
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: form.serialize(),
                                success: function() {
                                    addButton.attr("value", "Add");
                                    addButton.removeClass("unfriend-white-back");
                                    addButton.addClass("add-user-back");
                                }
                            });
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
})

$(".unfriend").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let unfriend = $(this);
    let form = unfriend.parent();

    /*
        We do that because some inputs are not directly child of the form so when followButton get clicked we need to start from this button
        to the first form ancestor and use it as form because in friend sub-option we have an inut inside div which is not a direct
        child of follow-form
    */
    while(form.prop("tagName") != "FORM") {
        form = form.parent();
    }

    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add follow record (This basically add some layer of security)
                url = root + "api/user_relation/unfriend_relation.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["success"]) {
                            location.reload();
                        } else {
                            console.log("Open console to see API request response !");
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
})

$(".accept-user").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let unfriend = $(this);
    let form = unfriend.parent();

    /*
        We do that because some inputs are not directly child of the form so when followButton get clicked we need to start from this button
        to the first form ancestor and use it as form because in friend sub-option we have an inut inside div which is not a direct
        child of follow-form
    */
    while(form.prop("tagName") != "FORM") {
        form = form.parent();
    }

    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add follow record (This basically add some layer of security)
                url = root + "api/user_relation/accept_request.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["success"]) {
                            location.reload();
                        } else {
                            console.log("Open console to see API request response !");
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
});

$(".decline-user").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let unfriend = $(this);
    let form = unfriend.parent();

    /*
        We do that because some inputs are not directly child of the form so when followButton get clicked we need to start from this button
        to the first form ancestor and use it as form because in friend sub-option we have an inut inside div which is not a direct
        child of follow-form
    */
    while(form.prop("tagName") != "FORM") {
        form = form.parent();
    }

    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add follow record (This basically add some layer of security)
                url = root + "api/user_relation/decline_request.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["success"]) {
                            location.reload();
                        } else {
                            console.log("Open console to see API request response !");
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
});

const get_thumbnail = async function(file, seekTo, component) {
    let response = await getVideoCover(file, seekTo);

    component.find(".video-post-thumbnail").attr("src", response);
    console.log("what we get: ");
    console.log(response);
}

function createPoster($video) {
    var canvas = document.createElement("canvas");
    canvas.width = 350;
    canvas.height = 350;
    canvas.getContext("2d").drawImage($video, 0, 0, canvas.width, canvas.height);
    return canvas.toDataURL("image/jpeg");;
}

function getVideoCover(file, seekTo = 0.0) {
    console.log("getting video cover for file: ", file);
    return new Promise((resolve, reject) => {
        // load the file to a video player
        const videoPlayer = document.createElement('video');
        videoPlayer.setAttribute('src', URL.createObjectURL(file));
        videoPlayer.load();
        videoPlayer.addEventListener('error', (ex) => {
            reject("error when loading video file", ex);
        });
        // load metadata of the video to get video duration and dimensions
        videoPlayer.addEventListener('loadedmetadata', () => {
            // seek to user defined timestamp (in seconds) if possible
            if (videoPlayer.duration < seekTo) {
                reject("video is too short.");
                return;
            }
            // delay seeking or else 'seeked' event won't fire on Safari
            setTimeout(() => {
              videoPlayer.currentTime = seekTo;
            }, 200);
            // extract video thumbnail once seeking is complete
            videoPlayer.addEventListener('seeked', () => {
                console.log('video is now paused at %ss.', seekTo);
                // define a canvas to have the same dimension as the video
                const canvas = document.createElement("canvas");
                canvas.width = videoPlayer.videoWidth;
                canvas.height = videoPlayer.videoHeight;
                // draw the video frame to canvas
                const ctx = canvas.getContext("2d");
                ctx.drawImage(videoPlayer, 0, 0, canvas.width, canvas.height);
                // return the canvas image as a blob
                ctx.canvas.toBlob(
                    blob => {
                        resolve(createPoster(videoPlayer));
                    },
                    "image/jpeg",
                    0.75 /* quality */
                );
            });
        });
    });
}

function handle_post_assets(post) {
    let media_containers = post.find(".post-media-item-container");
    let num_of_medias = $(post).find(".post-media-item-container").length;

    // Here the appearance of images in post will be different depends on the number of images
    if(num_of_medias == 1) {

        console.log("I'm here !");

        if($(post).find(".post-media-image").height() > $(post).find(".post-media-image").width()) {
            $(post).find(".post-media-image").width("100%");
        } else if ($(post).find(".post-media-image").height() > $(post).find(".post-media-image").width()){
            $(post).find(".post-media-image").height("100%");
        } else {
            $(post).find(".post-media-image").height("100%");
            $(post).find(".post-media-image").width("100%");
        }

        return;
    }
    if(num_of_medias == 2) {
        for(let k = 0;k<num_of_medias; k++) {
            $(media_containers[k]).css("width", half_width_marg + 3);
            $(media_containers[k]).css("height", full_height_marg + 3);
            $(media_containers[k]).find(".post-media-image").height("100%");
        }

        $(media_containers[0]).css("margin-right", "3px");
        $(media_containers[1]).css("margin-left", "3px");

    } else if(num_of_medias == 3) {
        for(let k = 0;k<2; k++) {
            let ctn = media_containers[k];

            $(ctn).css("width", half_width_marg);
            $(ctn).css("height", half_height_marg);
            if($(ctn).find(".post-media-image").height() >= $(ctn).find(".post-media-image").width()) {
                $(ctn).find(".post-media-image").width("100%");
            } else {
                $(ctn).find(".post-media-image").height("100%");
            }
        }

        $(media_containers[0]).css("margin-right", "3px");
        $(media_containers[1]).css("margin-left", "3px");

        let ctn = media_containers[2];
        $(ctn).css("margin-top", "3px");
        $(ctn).css("width", full_width_marg + 3);
        $(ctn).css("height", half_height_marg + 3);

        if($(ctn).find(".post-media-image").height() >= $(ctn).find(".post-media-image").width()) {
            $(ctn).find(".post-media-image").width("100%");
        } else {
            $(ctn).find(".post-media-image").height("100%");
        }

    } else if(num_of_medias == 4) {
        for(let k = 0;k<4; k++) {
            let ctn = media_containers[k];
            $(ctn).css("align-items", "self-start");
            $(ctn).css("margin", "3px");
            $(ctn).css("width", half_width_marg);
            $(ctn).css("height", half_height_marg);

            if($(ctn).find(".post-media-image").height() >= $(ctn).find(".post-media-image").width()) {
                $(ctn).find(".post-media-image").width("100%");
            } else {
                $(ctn).find(".post-media-image").height("100%");
            }
        }
    } else if(num_of_medias > 4){
        media_containers.css("align-items", "self-start")
        let ctn = media_containers;
        for(let k = 0;k<4; k++) {
            ctn = media_containers[k];

            $(ctn).css("margin", "3px");
            $(ctn).css("width", half_width_marg);
            $(ctn).css("height", half_height_marg);

            if($(ctn).find(".post-media-image").height() >= $(ctn).find(".post-media-image").width()) {
                $(ctn).find(".post-media-image").width("100%");
            } else {
                $(ctn).find(".post-media-image").height("100%");
            }
        }

        let plus = num_of_medias - 4;
        for(let j = 4;j<num_of_medias;j++) {
            $(media_containers[j]).remove();
        }
        $(media_containers[3]).append("<div class='more-posts-items'><h1>+" + plus + "</h1></div>");
        $(".more-posts-items").click(function() {
            go_to_post($(this));
        });
    }
}