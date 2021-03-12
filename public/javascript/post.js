

let posts_images = $(".post-media-image");

let container_width = $("#posts-container").width();
let max_container_height = 500;

let posts = $(".post-item");

let half_width_marg = container_width / 2 - 6; // we divide the whole container's width by 2 and take 6 off to matches margin: 3px (left and right)
let half_height_marg = max_container_height / 2 - 6; // we divide the whole container's width by 2 and take 6 off to matches margin: 3px (left and right)
let full_width_marg = container_width - 6;
let full_height_marg = max_container_height - 6;

for(let i = 0;i<posts.length;i++) {
    let media_containers = $(posts[i]).find(".post-media-item-container");
    let num_of_medias = $(posts[i]).find(".post-media-item-container").length;

    // Here the appearance of images in post will be different depends on the number of images
    if(num_of_medias == 2) {
        for(let k = 0;k<num_of_medias; k++) {
            let ctn = media_containers[k];
    
            $(ctn).css("width", half_width_marg + 3);
            $(ctn).css("height", full_height_marg + 3);
            $(ctn).find(".post-media-image").height("100%");
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
        let ctn = media_containers[i];
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

$('.media-container').each(function(i, obj) {
    if($(this).find(".post-media-item-container").length == "1") {

        let image_height = $(obj).find(".post-media-image").height();
        let image_width = $(obj).find(".post-media-image").width();

        if(image_height >= image_width) {
            $(obj).find(".post-media-image").css("width", container_width);
        } else {
            $(obj).find(".post-media-image").css("height", max_container_height);
        }
    }
});

$(".close-view-post").click(function() {
    $(".post-viewer-only").css("display", "none");
    $("body").css("overflow-y", "scroll");
});

$(".post-viewer-only").css("height", $(window).height() - 55);

$(".post-view-button").click(function() {
    view_image($(this).parent());
});

$(window).resize(function() {
    $(".post-viewer-only").css("height", $(window).height() - 55);
})

$(".post-viewer-only").click(function() {
    $(this).css("display", "none");
    $("body").css("overflow-y", "scroll");
});

$(".post-view-image").click(function(event) {
    event.stopPropagation();
})

function go_to_post(post) {
    let post_container = post;

    while(!post_container.hasClass("post-item")) {
        post_container = post_container.parent();
    }

    let post_id = post_container.find(".pid").val();

    // Check if the post is image(s) only post
    if(post_container.find(".image-post")) {
        window.location.href = root + "/post-viewer.php?pid=" + post_id;
    }
}

$(".comment-input").each(function(index, comment) {
    handle_comment_input(comment);
});

function handle_comment_event(element) {
    let suboption_container = $(element).find(".sub-options-container");
    let suboption_button = $(element).find(".comment-options-button");

    $(suboption_button).click(function(event) {
        if($(suboption_container).css("display") == "none") {
            $(".comment-block").find(".sub-options-container").css("display", "none");
            $(suboption_container).css("display", "block");
        } else {
            $(suboption_container).css("display", "none");
        }

        event.stopPropagation();
    });

    $(".comment-block").on({
        mouseenter: function() {
            $(this).find(".comment-options-button").css("opacity", "1");
        },
        mouseleave: function() {
            $(this).find(".comment-options-button").css("opacity", "0");
        }
    })

    // Handle hide comment button
    let hide = $(element).find(".hide-button");
    $(hide).click(function() {
        let container = $(this);
        while(!container.hasClass("comment-block")) {
            container = container.parent();
        }

        container.find(".comment-op").css("display", "none");
        container.find(".comment-global-wrapper").css("display", "none");
        container.find(".sub-options-container").css("display", "none");

        container.find(".hidden-comment-hint").css("display", "block");

        return false;
    });
    // Handle show comment after hide it
    let show_comment = $(element).find(".show-comment");
    $(show_comment).click(function() {
        let container = $(this);
        while(!container.hasClass("comment-block")) {
            container = container.parent();
        }

        container.find(".comment-op").css("display", "block");
        container.find(".comment-global-wrapper").css("display", "block");

        container.find(".hidden-comment-hint").css("display", "none");

        return false;
    });
    
    // Handle comment deletion
    let delete_comment = $(element).find(".delete-comment");
    $(delete_comment).click(function() {
        let container = $(this);
        while(!container.hasClass("comment-block")) {
            container = container.parent();
        }

        let cid = container.find(".comment_id").val();
        $.ajax({
            url: root + "api/comment/delete.php",
            type: 'POST',
            data: {
                comment_id: cid
            },
            success(response) {
                if(response == 1) {
                    let post_container = delete_comment;
                    while(!post_container.hasClass("post-item")) {
                        post_container = post_container.parent();
                    }

                    let count = post_container.find(".post-meta-comments").find(".meta-count").html();
                    let comment_counter = (count == "0") ? 0 : parseInt(count);

                    if(comment_counter == 1) {
                        post_container.find(".post-meta-comments").addClass("no-display");
                        comment_counter = 0;
                    } else {
                        post_container.find(".post-meta-comments").find(".meta-count").html(--comment_counter);
                    }

                    container.find(".sub-options-container").css("display", "none");
                    container.remove();
                }
            }
        });

        return false;
    });

    $(".close-edit").click(function() {
        let container = $(this);
        while(!container.hasClass("comment-block")) {
            container = container.parent();
        }
        container.find(".comment-text").css("display", "block");
        $(this).parent().css("display", "none");
    });

    let edit_comment = $(element).find(".edit-comment");
    $(edit_comment).click(function() {
        let container = $(this);
        while(!container.hasClass("comment-block")) {
            container = container.parent();
        }

        container.find(".sub-options-container").css("display", "none");

        let cid = container.find(".comment_id").val();
        let comment = container.find(".comment-text").text();

        container.find(".comment-text").css("display", "none");

        container.find(".comment-edit-container").find(".comment-editable-text").val(comment);
        container.find(".comment-edit-container").css("display", "block");
        container.find(".comment-edit-container").find(".comment-editable-text").focus();
        
        $(".comment-editable-text").on({
            keydown: function(event) {
                if($(this).is(":focus") && (event.keyCode == 13) && $(this).css("display") != "none") {
                    if (event.keyCode == 13 && !event.shiftKey) {
                        if($(this).val() != container.find(".comment-text").text()) {
                            event.preventDefault();

                            let new_com = container.find(".comment-edit-container").find(".comment-editable-text").val();
                            $.ajax({
                                url: root + "api/comment/edit.php",
                                type: 'post',
                                data: {
                                    new_comment: new_com,
                                    comment_id: cid,
                                },
                                success: function(response) {
                                    if(response) {
                                        container.find(".comment-edit-container").css("display", "none");
                                        container.find(".comment-text").css("display", "block");
                                        container.find(".comment-text").text(response);
                                    }
                                }
                            })

                        } else {
                            container.find(".comment-edit-container").css("display", "none");
                            container.find(".comment-text").css("display", "block");
                        }
                    }
                }
            }
        })

        return false;
    });
}

$(".post-meta-comments").click(function() {
    let comment_section = $(this);
    while(!comment_section.hasClass("post-item")) {
        comment_section = comment_section.parent();
    }
    comment_section = comment_section.find(".comment-section");

    $('html, body').animate({
        'scrollTop' : comment_section.position().top
    }, 500);
})

$(".comment-block").each(function(index, block) {
    handle_comment_event(block);
})

handle_comment_event();

$(".write-comment-button").each(function(index, comment_button) {
    handle_coment_button(comment_button);
})

$(".like-button").click(function(event) {
    handle_like_button($(this));
    event.preventDefault();
});

$(".share-button").click(function(event) {
    handle_share_button($(this));

    event.preventDefault();
});

function view_image(media_container) {
    $(".post-viewer-only").css("display", "flex");
    
    $(".post-view-image").attr("src", $(media_container).find(".post-media-image").attr("src"));

    if($(".post-view-image").height() >= $(".post-view-image").width) {
        $(".post-view-image").width("100%");
    } else {
        $(".post-view-image").height("100%");
    }

    $("body").css("overflow-y", "hidden");
}

function handle_post_assets(post) {
    let media_containers = post.find(".post-media-item-container");
    let num_of_medias = $(post).find(".post-media-item-container").length;

    // Here the appearance of images in post will be different depends on the number of images
    if(num_of_medias == 1) {
        if($(post).find(".post-media-image").height() > $(post).find(".post-media-image").width()) {
            $(post).find(".post-media-image").width("100%");
        } else if ($(post).find(".post-media-image").height() > $(post).find(".post-media-image").width()){
            $(post).find(".post-media-image").height("100%");
        } else {
            $(post).find(".post-media-image").width("100%");
        }

        $(post).find(".post-view-button").click(function() {
            view_image($(post));
        });

        return;
    }
    if(num_of_medias == 2) {
        for(let k = 0;k<num_of_medias; k++) {
            $(media_containers[k]).css("width", half_width_marg + 3);
            $(media_containers[k]).css("height", full_height_marg + 3);
            $(media_containers[k]).find(".post-media-image").height("100%");

            $(media_containers[k]).find(".post-view-button").click(function() {
                view_image(media_containers[k]);
            });
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

            $(ctn).find(".post-view-button").click(function() {
                view_image($(ctn));
            });
        }

        $(media_containers[0]).css("margin-right", "3px");
        $(media_containers[1]).css("margin-left", "3px");

        let ctn = media_containers[2];
        $(ctn).css("margin-top", "3px");
        $(ctn).css("width", full_width_marg + 3);
        $(ctn).css("height", half_height_marg + 3);
        $(ctn).find(".post-view-button").click(function() {
            view_image(ctn);
        });

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

            $(ctn).find(".post-view-button").click(function() {
                view_image(ctn);
            });
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

            $(ctn).find(".post-view-button").click(function() {
                view_image(ctn);
            });
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
                url: root + "layouts/post/generate_last_post.php",
                success: function(component) {
                    if($('#empty-posts-message').length != 0) {
                        $('#empty-posts-message').remove();
                    }
                    $("#posts-container").prepend(component);
                    let post = $(".post-item").first();
                    // Handle post if it contains images or videos
                    handle_post_assets(post);
                    // Handle like, comment, and share buttons
                    handle_post_buttons_actions(post);
                    // Handle delete, edit, and hide post buttons
                    handle_post_actions(post);
                    handle_post_options_subcontainer(post);
                    handle_go_to_post(post);
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

            /*
                After sharing the post share-post token is removed from server session, so we need to regenerate one
                by storing a new one in server session and get it as response from ajax request and put it to share-post
                ttoken hidden input
            */
            $.ajax({
                type: 'POST',
                url: root+'security/generate_new_token_post.php',
                data: {
                    token_name: "token_post"
                },
                success: function(response) {
                    $("#share_post_token").val(response);
                }
            })
        },
        error: function(){
            console.log('error');
        }
    });
});

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
        url: root + "layouts/post/generate_post_creation_image.php",
        success: function(response) {

            let container = response;

            // We check if there's no file and text area is empty we hide the share button
            if(files.length == 0 && $("#create-post-textual-content").val() == "") {
                $("#post-create-button").css("display", "none");
            } else {
                $("#post-create-button").css("display", "block");
                $(".share-post").css("display", "block");
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

    $.ajax({
        type: 'GET',
        url: root + "layouts/post/generate_post_creation_video.php",
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

// Later try to track string where changed to add symbols like <3 to love symbol and so on
$("#create-post-textual-content").on({
    keyup: function() {
        if($(this).val() != "") {
            $("#post-create-button").css("display", "block");
            $(".share-post").css("display", "block");
        } else {
            if($("#post-assets").val() == "") {
                $("#post-create-button").css("display", "none");
            }
        }
    }
});

// This function adjust indexes of uploaded images when user wants to delete an image from a set of uploaded images before sharing the post
function adjust_post_uploaded_assets_indexes() {
    let counter = 0;
    $(".post-creation-item").each(function() {
        $(this).find(".pciid").val(counter);
        counter++;
    });
}

// The following two function used to validate uploaded image or video
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

// The following three functions used to fetch image thumbnail from the uploaded video if user upload a video
const get_thumbnail = async function(file, seekTo, component) {
    let response = await getVideoCover(file, seekTo);

    component.find(".video-post-thumbnail").attr("src", response);
}
function createPoster($video) {
    var canvas = document.createElement("canvas");
    canvas.width = 350;
    canvas.height = 350;
    canvas.getContext("2d").drawImage($video, 0, 0, canvas.width, canvas.height);
    return canvas.toDataURL("image/jpeg");;
}
function getVideoCover(file, seekTo = 0.0) {
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

// This function ->handle_post_buttons_actions: handle like, comment and share buttons by calling the next four functions
function handle_post_buttons_actions(post) {
    let like_button = $(post).find('.like-button');
    let comment_input = $(post).find('.comment-input');
    let comment_button = $(post).find('.write-comment-button');
    let share_button = $(post).find('.share-button');

    handle_like_button(like_button);
    handle_comment_input(comment_input);
    handle_coment_button(comment_button);
    handle_share_button(share_button);
}
function handle_like_button(like_button) {
    like_button.click(function(event) {
        let container = like_button;
        while(!container.hasClass("post-item")) {
            container = container.parent();
        }
        //let pid = container.find(".pid").val();
        let pid = container.find(".pid").last().val();
    
        $.ajax({
            url: root + "api/like/post.php",
            type: 'post',
            data: {
                post_id: pid,
                current_user_id: current_user_id
            },
            success: function(response) {
                /*
                    1: added successfully
                    2: deleted successfully
                    -1: there's a problem
                */
                let count = container.find(".post-meta-likes").find(".meta-count").html();
                let likes_counter = (count == "0") ? 0 : parseInt(container.find(".post-meta-likes").find(".meta-count").html());
                if(response == 1) {
                    $(like_button).removeClass("white-like-back");
                    $(like_button).addClass("white-like-filled-back");
                    $(like_button).addClass("bold");
    
                    if(container.find(".post-statis").css("display") == "none") {
                        container.find(".post-statis").css("display", "flex");
                    }
                    
                    likes_counter = likes_counter + 1;
                    container.find(".post-meta-likes").find(".meta-count").html(likes_counter);
                    container.find(".post-meta-likes").removeClass("no-display");
    
                } else if(response == 2) {
                    if(likes_counter == 1) {
                        container.find(".post-meta-likes").addClass("no-display");
                        likes_counter = 0;
                    } else {
                        container.find(".post-meta-likes").find(".meta-count").html(--likes_counter);
                    }
    
                    $(like_button).addClass("white-like-back");
                    $(like_button).removeClass("white-like-filled-back");
                    $(like_button).removeClass("bold");
                    container.find(".post-meta-likes").find(".meta-count").html(likes_counter);
                }
            }
        })

        event.preventDefault();
    })
}
function handle_comment_input(comment_input) {
    $(comment_input).on({
        keydown: function(event) {
            let comment = $(comment_input);
            if($(comment_input).is(":focus") && (event.keyCode == 13)) {
                if($(comment_input).val() != "") {
                    let post_container = $(comment_input);
                    while(!post_container.hasClass("post-item")) {
                        post_container = post_container.parent();
                    }
                    //let post_id = post_container.find(".pid").val();
    
                    /*
                        Here we get the last pid because in case we have a shared post, we'll have two pid inputs, one for the original post
                        and the other for the shared post, so we need the shared post id for comment to attach the comment to the shared post
                        and the same approach for liking posts as well as sharing the post
                    */
                    let post_id = post_container.find(".pid").last().val();
                    $.ajax({
                        url: root + "security/get_current_user.php",
                        success(response) {
                            let comment_owner = response.id;
                            let comment_text = comment.val();
    
                            $.ajax({
                                url: root+"api/comment/post.php",
                                data: {
                                    "comment_owner": comment_owner,
                                    "post_id": post_id,
                                    "comment_text": comment_text,
                                    "current_user_id": current_user_id
                                },
                                type: 'POST',
                                success: function(response) {
    
                                    // Update number of comments
                                    let count = post_container.find(".post-meta-comments").find(".meta-count").html();
                                    let comments_counter = (count == "0") ? 0 : parseInt(post_container.find(".post-meta-comments").find(".meta-count").html());
                                    if(post_container.find(".post-statis").css("display") == "none") {
                                        post_container.find(".post-statis").css("display", "flex");
                                    }
                                    
                                    comments_counter = comments_counter + 1;
                                    post_container.find(".post-meta-comments").find(".meta-count").html(comments_counter);
                                    post_container.find(".post-meta-comments").removeClass("no-display");
    
                                    // Emptying comment input
                                    comment.val("");
    
                                    // Get post comments container
                                    let comment_container = comment;
                                    while(!comment_container.hasClass("comment-section")) {
                                        comment_container = comment_container.parent();
                                    }
                                    
                                    comment_container.prepend(response);
                                    let component = comment_container.find(".comment-block").first();
                                    component.find(".comment_id").val();
                                    handle_comment_event(component);
    
                                    if(post_container.find(".post-statis").css("display") == "none") {
                                        $(".post-statis").css("display", "flex");
                                    }
                                }
                            })
                        }
                    });
                } else {
                    console.log("empty comment !");
                }
                return false;
            }
        }
    });
}
function handle_coment_button(comment_button) {
    $(comment_button).click(function(event) {
        let container = $(comment_button);
        while(!container.hasClass("post-item")) {
            container = container.parent();
        }
    
        container.find(".comment-input").focus();
    
        event.preventDefault();
    });
}
function handle_share_button(share_button) {
    share_button.click(function(event) {
        let container = share_button;
        while(!container.hasClass("post-item")) {
            container = container.parent();
        }
        //let pid = container.find(".pid").val();
        let pid = container.find(".pid").val();
    
        share_button.css("opacity", "0");
        share_button.css("cursor", "default");
    
        share_button.parent().find(".share-animation-container").css("display", "flex");
        
        let count = container.find(".post-meta-shares").find(".meta-count").html();
        let shares_counter = (count == "0") ? 0 : parseInt(container.find(".post-meta-shares").find(".meta-count").html());
        
        $.ajax({
            url: root+"api/post/shared/add.php",
            type: "post",
            data: {
                post_owner: current_user_id,
                post_visibility: 1,
                post_place: 1,
                post_shared_id: pid
            },
            success: function(response) {
                if(response == 1) {
                    if(container.find(".post-statis").css("display") == "none") {
                        container.find(".post-statis").css("display", "flex");
                    }
                    
                    shares_counter = shares_counter + 1;
                    container.find(".post-meta-shares").find(".meta-count").html(shares_counter);
                    container.find(".post-meta-shares").removeClass("no-display");
        
                    container.find(".share-animation-container").css("display", "none");
                    container.find(".share-button").css("opacity", "1");
                    container.find(".share-button").css("cursor", "pointer");
        
                    $(".notification-bottom-sentence").text("Post shared in your timeline successfully !");
                    $(".notification-bottom-container").css("display", "block");
                    $(".notification-bottom-container").animate({
                        opacity: 1
                    }, 400);
                    setTimeout(function() { 
                        $(".notification-bottom-container").animate({
                            opacity: 0
                        }, 400);
                    }, 3000, function() {
                        $(".notification-bottom-container").css("display", "none");
                    });
                    $(".share-post").css('display', "none");
                } else {
                    // Show error occured message into screen !
                }
            }
        });

        event.preventDefault();
    });
}

// The following functions handle delete, edit and hide actions
function handle_post_actions(post) {
    try {
        handle_delete_post(post);
        handle_edit_post(post);
        handle_hide_post(post);
    } catch(error) {
        console.log("error: ");
        console.log(error);
    }
}

function handle_hide_post(post) {
    $(post).find('.hide-post').click(function() {
        $(post).append('<p class="small-text" style="padding: 10px 16px">This post is hidden. Click <a href="" class="show-again show-post-again">here</a> to see it again</p>')
        $(post).find(".show-post-again").click(function() {
            $(post).find('.timeline-post').css('display', 'block');
            $(this).parent().remove();

            $(".sub-options-container").css("display", "none");
            return false;
        })
        $(post).find('.timeline-post').css('display', 'none');
        return false;
    });
}
function handle_delete_post(post) {
    $(post).find('.delete-post').click(function(event) {
        // The reason why we want the last pid because in case of shared post we want the shared post to be deleted and not the original post
        // and because we embed shared post along with original post the id of shared post is the last one in DOM order
        let pid = $(post).find('.pid').last().val();

        $.ajax({
            url: root + 'api/post/delete.php',
            type: 'post',
            data: {
                post_id: pid,
                post_owner: current_user_id
            },
            success: function(response) {
                /*
                    Be carefull when you delete a post you need also to delete all likes comments and shared posts that
                    are related to that post. Also we go to every post that is a shared post for that deleted post and we customized it
                    by editing text to empty and add a section says: POST NOT FOUND
                */
                $(post).remove();
            }
        });
        
        event.preventDefault();
    });
}
function handle_edit_post(post) {
    let pid = $(post).find('.pid').last().val();

    $(post).find(".edit-post").click(function() {
        // First we hide the old text of post
        $(post).find(".post-text").css("display", 'none');
        // Then we show the edit container which contains the textatrea and exit button
        $(post).find(".post-edit-container").css("display", 'block');
        // Then we fill the textarea with the old post text and focus it
        $(post).find(".post-editable-text").val($(post).find(".post-text").text().trim());
        $(this).parent().parent().css("display", 'none');

        $(post).find(".post-editable-text").on({
            keydown: function(event) {
                if($(this).is(":focus") && (event.keyCode == 13) && $(this).css("display") != "none") {
                    if(event.keyCode == 13 && !event.shiftKey) {
                        if($(this).val().trim() != $(post).find(".post-text").text().trim()) {
                            event.preventDefault();

                            let new_post_text = $(post).find(".post-editable-text").val();
                            $.ajax({
                                url: root + "api/post/edit.php",
                                type: 'post',
                                data: {
                                    new_post_text: new_post_text,
                                    post_id: pid,
                                },
                                success: function(response) {
                                    if(response) {
                                        $(post).find(".post-edit-container").css("display", "none");
                                        $(post).find(".post-text").css("display", "block");
                                        $(post).find(".post-text").text(response);
                                    }
                                }
                            });
                        } else {
                            event.preventDefault();
                        }
                    }
                }
            }
        })


        return false;
    });

    $(post).find(".close-post-edit").click(function() {
        $(post).find(".post-text").css("display", 'block');
        // Then we show the edit container which contains the textatrea and exit button
        $(post).find(".post-edit-container").css("display", 'none');
    });
}

function handle_post_options_subcontainer(post) {
    $(post).find(".button-with-suboption").click(function() {
        let container = $(this).parent().find(".sub-options-container");
        if(container.css("display") == "none") {
            $(".sub-options-container").css("display", "none");
            container.css("display", "block");
        } else {
            container.css("display", "none");
        }
        return false;
    });
}

function handle_go_to_post(post) {
    $(post).find(".post-media-image").click(function() {
        go_to_post($(this));
    });
}

$('.post-item').each(function(index, post_item) {
    handle_post_actions(post_item);
    handle_go_to_post(post_item);
});