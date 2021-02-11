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
                                comment.val("");

                                // Get post comments container
                                let comment_container = comment;
                                while(!comment_container.hasClass("comment-section")) {
                                    comment_container = comment_container.parent();
                                }
                                
                                comment_container.prepend(response);
                                let component = comment_container.find(".comment-block").first().find(".button-with-suboption")[0];
                                handle_comment_event(component);
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
    console.log("handling !");
    $(element).click(function() {
        console.log("I'm working !");
        let container = $(element).parent().find(".sub-options-container");
        if(container.css("display") == "none") {
            $(".sub-options-container").css("display", "none");
            container.css("display", "block");
        } else {
            container.css("display", "none");
        }
        return false;
    });

    // Handle like and reply below
}