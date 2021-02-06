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
for(let i = 0;i<posts_images.length;i++) {
}

$('.post-media-image').each(function(i, obj) {
    let image_height = $(obj).height();
    let image_width = $(obj).width();

    if(image_height >= image_width) {
        $(obj).css("width", "100%");
    } else {
        $(obj).css("height", "100%");
    }
});

let posts = $(".post-item");
let half_width_marg = $(".media-container").width() / 2 - 6; // we divide the whole container's width by 2 and take 6 off to matches margin: 3px (left and right)
let half_height_marg = $(".media-container").height() / 2 - 6; // we divide the whole container's width by 2 and take 6 off to matches margin: 3px (left and right)
let full_width_marg = $(".media-container").width() - 6;
let full_height_marg = $(".media-container").height() - 6;
for(let i = 0;i<posts.length;i++) {
    let media_container = $(posts[i]).find(".media-container").get(0);
    let num_of_medias = $(media_container).find(".post-media-item-container").length;

    console.log(num_of_medias);
    
    // Here the appearance of images in post will be different depends on the number of images
    if(num_of_medias == 2) {
        $(posts[i]).find(".post-media-item-container").css("width", half_width_marg);
        $(posts[i]).find(".post-media-item-container").css("height", "100%");
        $(posts[i]).find(".post-media-item-container").css("margin", "3px");
    } else if(num_of_medias == 3) {
        let medias = $(media_container).find(".post-media-item-container");
        $(medias).css("margin", "3px");
        // Here if the post cotnains 3 images, we want to make two images in a row and the third one will take all the width
        // and all the images will have 50% of height of the global post container
        $(medias[0]).css("width", half_width_marg);
        $(medias[0]).css("height", half_height_marg);
        
        $(medias[1]).css("width", half_width_marg);
        $(medias[1]).css("height", half_height_marg);

        $(medias[2]).css("width", full_width_marg);
        $(medias[2]).css("height", half_height_marg);


    } else if(num_of_medias == 4) {
        let medias = $(media_container).find(".post-media-item-container");
        $(medias).css("margin", "3px");
        $(medias).css("width", half_width_marg);
        $(medias).css("height", half_height_marg);
    } else if(num_of_medias > 4){
        let medias = $(media_container).find(".post-media-item-container");
        let plus = num_of_medias - 4;

        $(medias).css("margin", "3px");
        $(medias[0]).css("width", half_width_marg);
        $(medias[0]).css("height", half_height_marg);
        
        $(medias[1]).css("width", half_width_marg);
        $(medias[1]).css("height", half_height_marg);

        $(medias[2]).css("width", half_width_marg);
        $(medias[2]).css("height", half_height_marg);

        $(medias[3]).css("width", half_width_marg);
        $(medias[3]).css("height", half_height_marg);

        $(medias[3]).append("<div class='more-posts-items'><h1>+" + plus + "</h1></div>");
    }
}