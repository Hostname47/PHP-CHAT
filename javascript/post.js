$(".post-media-image").click(function() {
    let post_container = $(this);
    while(!post_container.hasClass("post-item")) {
        post_container = post_container.parent();
    }

    let post_id = post_container.find(".pid").val();

    // Check if the post is image(s) only post
    if(post_container.find(".image-post")) {
        console.log("Yeah it's image post !");
        window.location.href = root + "post/post-skeleton.php?pid=" + post_id;
    }
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
    let media_container = $(posts[i]).find(".media-container").get(0);
    let num_of_medias = $(media_container).find(".post-media-item-container").length;
    
    // Here the appearance of images in post will be different depends on the number of images
    if(num_of_medias == 2) {
        let medias = $(media_container).find(".post-media-item-container");
        $(medias).css("margin", "3px");

        console.log("post-item-width: " + container_width)
        console.log("half-width: " + half_width_marg)
        $(posts[i]).find(".post-media-item-container").css("width", half_width_marg);
        $(posts[i]).find(".post-media-item-container").css("height", full_height_marg);
        $(posts[i]).find(".post-media-item-container").css("margin", "3px");

        if($(medias[0]).find(".post-media-image").height() >= $(medias[0]).find(".post-media-image").width()) {
            $(medias[0]).find(".post-media-image").width("100%");
        } else {
            $(medias[0]).find(".post-media-image").height("100%");
        }

        if($(medias[1]).find(".post-media-image").height() >= $(medias[1]).find(".post-media-image").width()) {
            $(medias[1]).find(".post-media-image").width("100%");
        } else {
            $(medias[1]).find(".post-media-image").height("100%");
        }

    } else if(num_of_medias == 3) {
        let medias = $(media_container).find(".post-media-item-container");
        $(medias).css("margin", "3px");
        // Here if the post cotnains 3 images, we want to make two images in a row and the third one will take all the width
        // and all the images will have 50% of height of the global post container
        $(medias[0]).css("width", half_width_marg);
        $(medias[0]).css("height", half_height_marg);

        if($(medias[0]).find(".post-media-image").height() >= $(medias[0]).find(".post-media-image").width()) {
            $(medias[0]).find(".post-media-image").width("100%");
        } else {
            $(medias[0]).find(".post-media-image").height("100%");
        }
        
        $(medias[1]).css("width", half_width_marg);
        $(medias[1]).css("height", half_height_marg);

        if($(medias[1]).find(".post-media-image").height() >= $(medias[1]).find(".post-media-image").width()) {
            $(medias[1]).find(".post-media-image").width("100%");
        } else {
            $(medias[1]).find(".post-media-image").height("100%");
        }

        $(medias[2]).css("width", full_width_marg);
        $(medias[2]).css("height", half_height_marg);
        $(medias[2]).find(".post-media-image").css("width", "100%");


    } else if(num_of_medias == 4) {
        let medias = $(media_container).find(".post-media-item-container");
        $(medias).css("margin", "3px");
        $(medias).css("width", half_width_marg);
        $(medias).css("height", half_height_marg);

        if($(medias[0]).find(".post-media-image").height() >= $(medias[0]).find(".post-media-image").width()) {
            $(medias[0]).find(".post-media-image").width("100%");
        } else {
            $(medias[0]).find(".post-media-image").height("100%");
        }

        if($(medias[1]).find(".post-media-image").height() >= $(medias[1]).find(".post-media-image").width()) {
            $(medias[1]).find(".post-media-image").width("100%");
        } else {
            $(medias[1]).find(".post-media-image").height("100%");
        }

        if($(medias[2]).find(".post-media-image").height() >= $(medias[2]).find(".post-media-image").width()) {
            $(medias[2]).find(".post-media-image").width("100%");
        } else {
            $(medias[2]).find(".post-media-image").height("100%");
        }

        if($(medias[3]).find(".post-media-image").height() >= $(medias[3]).find(".post-media-image").width()) {
            $(medias[3]).find(".post-media-image").width("100%");
        } else {
            $(medias[3]).find(".post-media-image").height("100%");
        }

    } else if(num_of_medias > 4){
        let medias = $(media_container).find(".post-media-item-container");
        let plus = num_of_medias - 4;

        console.log("I'm here !");

        $(medias).css("margin", "3px");
        $(medias[0]).css("width", half_width_marg);
        $(medias[0]).css("height", half_height_marg);

        if($(medias[0]).find(".post-media-image").height() >= $(medias[0]).find(".post-media-image").width()) {
            $(medias[0]).find(".post-media-image").width("100%");
        } else {
            $(medias[0]).find(".post-media-image").height("100%");
        }
        
        $(medias[1]).css("width", half_width_marg);
        $(medias[1]).css("height", half_height_marg);

        if($(medias[1]).find(".post-media-image").height() >= $(medias[1]).find(".post-media-image").width()) {
            $(medias[1]).find(".post-media-image").width("100%");
        } else {
            $(medias[1]).find(".post-media-image").height("100%");
        }

        $(medias[2]).css("width", half_width_marg);
        $(medias[2]).css("height", half_height_marg);

        if($(medias[2]).find(".post-media-image").height() >= $(medias[2]).find(".post-media-image").width()) {
            $(medias[2]).find(".post-media-image").width("100%");
        } else {
            $(medias[2]).find(".post-media-image").height("100%");
        }

        $(medias[3]).css("width", half_width_marg);
        $(medias[3]).css("height", half_height_marg);

        if($(medias[3]).find(".post-media-image").height() >= $(medias[3]).find(".post-media-image").width()) {
            $(medias[3]).find(".post-media-image").width("100%");
        } else {
            $(medias[3]).find(".post-media-image").height("100%");
        }

        $(medias[3]).append("<div class='more-posts-items'><h1>+" + plus + "</h1></div>");
        $(".more-posts-items").css("width", half_width_marg);
        $(".more-posts-items").css("height", half_height_marg);

        for(let j = 4;j<num_of_medias;j++) {
            $(medias[j]).remove();
            console.log("remove");
        }
    }
}

$('.media-container').each(function(i, obj) {
    if($(this).find(".post-media-item-container").length == "1") {

        let image_height = $(obj).find(".post-media-image").height();
        let image_width = $(obj).find(".post-media-image").width();

        if(image_height >= image_width) {
            $(obj).find(".post-media-image").css("width", full_width_marg);
        } else {
            $(obj).find(".post-media-image").css("height", full_height_marg);
        }
    }
});