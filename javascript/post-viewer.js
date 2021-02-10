
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
        if(current_index == 0) {
            $(".asset-back").css("display", "none");
            $(".asset-next").css("display", "flex");
            $(".asset-move-button").animate({
                opacity: 1
            }, 200);
        } else if(current_index != 0 && current_index != images_length-2) {
            $(".asset-back").css("display", "flex");
            $(".asset-next").css("display", "flex");
            $(".asset-move-button").animate({
                opacity: 1
            }, 200);
        } else {
            $(".asset-back").css("display", "flex");
            $(".asset-next").css("display", "none");
            $(".asset-move-button").animate({
                opacity: 1
            }, 200);
        }
    },
    mouseleave: function() {
        if(current_index != 0) {
            $(".asset-move-button").css("display", "none");
            $(".asset-move-button").animate({
                opacity: 0
            }, 200);
        }
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
} else {
    $(".collapse-text").css("display", "none");
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

$(".exit-button").click(function(event) {
    /*
        We need some way to go back to the ame page without refreshing, because posts are arraged randomly if the page reloaded
        one more time, and so we'll miss the place where the user click on the post; we need a way to keep the page in its place
        Hint: maybe appending the content of post page to index is an idea and when the user click exit we delete the appended page
    */
    window.history.back();
});

// Get the first image's src and past it to the post viewer image
$(".asset-image").attr("src", $(".images").find(".post-asset-image").first().val());
$(".asset-back").css("display", "none");

// If the post has only one image, then we don't have to have navigation buttons
let current_index = $(".current-asset-image").val();
let images_length =$(".images").find(".post-asset-image").length;

if(images_length == 1) {
    $(".asset-move-button").remove();
} else {
    // If it does, then we handle navigation buttons

    /*
        Next button: we first get the current image position then we move forward. Notice when we reach the last 
        element we fetch the first one
        IMPORTANT: Later try to add reactions on each image so that when we fetch the next item we send a request to the 
        server to get informations about the fetched image and set response data to the components in the right side
    */
    $(".asset-next").click(function() {

        $(".asset-back").css("display", "flex");
        if(current_index == images_length-2) {
            $(".asset-next").css("display", "none");
            let next_image_src = $(".images").find(".post-asset-image").eq(++current_index).val();
            $(".asset-image").attr("src", next_image_src);
            return;
        }
        
        let next_image_src = $(".images").find(".post-asset-image").eq(++current_index).val();
        $(".asset-image").attr("src", next_image_src);
        $(".current-asset-image").val(current_index)
    });

    $(".asset-back").click(function() {
        $(".asset-next").css("display", "flex");
        if(current_index == 1) {
            $(".asset-back").css("display", "none");
            let next_image_src = $(".images").find(".post-asset-image").eq(--current_index).val();
            $(".asset-image").attr("src", next_image_src);
            return;
        }

        let next_image_src = $(".images").find(".post-asset-image").eq(--current_index).val();
        $(".asset-image").attr("src", next_image_src);
        $(".current-asset-image").val(current_index)
    });
}