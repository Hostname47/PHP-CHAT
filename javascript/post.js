$(".post-media-image").click(function() {
    go_to_post($(this));
});

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
    $(".post-viewer-only").css("display", "flex");
    
    $(".post-view-image").attr("src", $(this).parent().find(".post-media-image").attr("src"));

    if($(".post-view-image").height() >= $(".post-view-image").width) {
        $(".post-view-image").width("100%");
    } else {
        $(".post-view-image").height("100%");
    }

    $("body").css("overflow-y", "hidden");
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
        window.location.href = root + "post/post-skeleton.php?pid=" + post_id;
    }
}

$(".comment-input").on({
    keydown: function(event) {
        let comment = $(this);
        if($(this).is(":focus") && (event.keyCode == 13)) {
            if($(this).val() != "") {
                let post_container = $(this);
                while(!post_container.hasClass("post-item")) {
                    post_container = post_container.parent();
                }
                let post_id = post_container.find(".pid").val();
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

//find(".comment-block")
handle_comment_event();

$(".write-comment-button").click(function() {
    let container = $(this);
    while(!container.hasClass("post-item")) {
        container = container.parent();
    }

    container.find(".comment-input").focus();

    return false;
})

$(".like-button").click(function(event) {
    let like_button = $(this);
    let container = $(this);
    while(!container.hasClass("post-item")) {
        container = container.parent();
    }
    let pid = container.find(".pid").val();

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
                console.log("counter: " + likes_counter);
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

$(".share-button").click(function(event) {

    let container = $(this);
    while(!container.hasClass("post-item")) {
        container = container.parent();
    }
    let pid = container.find(".pid").val();

    $(this).css("opacity", "0");
    $(this).css("cursor", "default");

    $(this).parent().find(".share-animation-container").css("display", "flex");
    
    let count = container.find(".post-meta-shares").find(".meta-count").html();
    let shares_counter = (count == "0") ? 0 : parseInt(container.find(".post-meta-shares").find(".meta-count").html());
    
    $.ajax({
        url: root+"api/post/shared/add.php",
        type: "post",
        data: {
            post_id: pid,
            poster_id: current_user_id
        },
        success: function() {
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
            }, 3000, function() {$(".notification-bottom-container").css("display", "none");});
            $(".share-post").css('display', "none");
        }
    })

    event.preventDefault();
});