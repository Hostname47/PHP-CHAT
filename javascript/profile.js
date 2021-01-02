
$(".profile-picture").css("borderColor", $("#first-section").css("backgroundColor"));


$(".viewer").css({
    width: $("body").width(),
    height: $("body").height()
})

$(".close-viewer").click(function() {
    $(".viewer").css("display", "none");

    return false;
})

$("#edit-profile-button").on({
    click: function() {
        let viewer = $(this).parent().find(".viewer");
        viewer.css("display", "block");
        return false;
    }
});

$(".cover-photo").on({
    click: function() {
        let viewer = $(this).parent().parent().find(".viewer");
        viewer.find(".profile-cover-picture-preview").attr("src", $(".cover-photo").attr("src"));
        viewer.css("display", "block");
        return false;
    }
});

$(".profile-picture").on({
    mouseenter: function() {
        $(".shadow-profile-picture").css("opacity", "0.2");
    },
    mouseleave: function() {
        $(".shadow-profile-picture").css("opacity", "0");
    },
    click: function() {
        let viewer = $(this).parent().parent().find(".viewer");
        viewer.css("display", "block");
        viewer.find(".profile-picture-preview").attr("src", $(".profile-picture").attr("src"));
        return false;
    }
});

$("#cover-changer-container").on({
    mouseenter: function() {
        $(this).find("#cover-changer-shadow, .change-image-icon").css("display", "block");
    },
    mouseleave: function() {
        $(this).find("#cover-changer-shadow, .change-image-icon").css("display", "none");
    }
});

$("#picture-changer-container").on({
    mouseenter: function() {
        $(this).find(".former-picture-shadow, .change-image-icon").css("display", "block");
    },
    mouseleave: function() {
        $(this).find(".former-picture-shadow, .change-image-icon").css("display", "none");
    }
});

$(".profile-picture-preview, .profile-cover-picture-preview, #edit-profile-container").click(function(event) {
    event.stopPropagation();
})

$(".viewer").click(function() {
    $(this).css("display", "none");
})

$(".user-info-section-link").on( {
    mouseenter: function() {
        $(this).find("div p").css("textDecoration", "underline");
    },
    mouseleave: function() {
        $(this).find("div p").css("textDecoration", "none");
    }
}
);

$(".user-info-square-shape").css("height", $(".user-info-square-shape").css("width"))

$("#change-cover-button").click(function() {
    
    return false;
});
