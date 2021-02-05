
$("#post-viewer").height($(window).height() - 55);

let asset_image = $(".asset-image")
let image_height = asset_image.height();
let image_width = asset_image.width();

if(image_height > image_width) {
    asset_image.css("width", "100%");
} else {
    asset_image.css("height", "100%");
}

$("#post-assets-container").on({
    mouseenter: function() {
        $(".asset-move-button").css("display", "block");
        $(".asset-move-button").animate({
            opacity: 1
        }, 200);
    },
    mouseleave: function() {
        $(".asset-move-button").css("display", "none");
        $(".asset-move-button").animate({
            opacity: 0
        }, 200);
    }
})

$(".asset-move-button").on({
    mouseenter: function() {
        $(this).css("backgroundColor", "rgb(16, 16, 16)");
    },
    mouseleave: function() {
        $(this).css("backgroundColor", "rgb(10, 10, 10)");
    }
});

window.onresize = function() {
    $("#post-viewer").height($(window).height() - 55);
}

if($(".post-text").text().length > 200) {
    $(".post-text").text($(".post-text").text().substr(0, 199) + "..");
}
$(".collapse-text").click(function() {
    if($(this).text() == "See more") {
        $(this).text("See less");
        $(".post-text").text($(".hidden-post-text").text());
    } else {
        $(this).text("See more");
        $(".post-text").text($(".post-text").text().substr(0, 199) + "..");
    }
});



$(".exit-button").hover(function(event) {
    /*
        We need some way to go back to the ame page without refreshing, because posts are arraged randomly if the page reloaded
        one more time, and so we'll miss the place where the user click on the post; we need a way to keep the page in its place
        Hint: maybe appending the content of post page to index is an idea and when the user click exit we delete the appended page
    */
    window.history.back();
});