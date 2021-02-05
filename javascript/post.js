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

    if(image_height > image_width) {
        $(obj).css("width", "100%");
    } else {
        $(obj).css("height", "100%");
    }
});