
$(".profile-picture").css("borderColor", $("#first-section").css("backgroundColor"));

let p_p_max_height = 150;
let profile_p_height = $(".profile-picture").height();
let profile_p_width = $(".profile-picture").width();


let one_hundred_perc = profile_p_height + profile_p_width;

let height_perc = profile_p_height * 100 / one_hundred_perc;
let width_perc = profile_p_width * 100 / one_hundred_perc;

$(".profile-picture").height(p_p_max_height);
let calc_width = width_perc * p_p_max_height / height_perc;
$(".profile-picture").width(calc_width);

$(".viewer").css({
    width: $("body").width(),
    height: $("body").height()
})

$(".close-viewer").click(function() {
    $(".viewer").css("display", "none");
    $("body").css("overflow-y", "scroll");
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

        let max_height = 500;

        let height = $(".profile-cover-picture-preview").height();
        let width = $(".profile-cover-picture-preview").width();

        let one_hundred_perc = height + width;

        let height_perc = height * 100 / one_hundred_perc;
        let width_perc = width * 100 / one_hundred_perc;
        
        if(width_perc > height_perc) {
            $(".profile-cover-picture-preview").height(max_height);
            let calc_width = width_perc * max_height / height_perc;
            $(".profile-cover-picture-preview").width(calc_width);
        } else {
            $(".profile-cover-picture-preview").height(max_height);
            let calc_width = width_perc * max_height / height_perc;
            $(".profile-cover-picture-preview").width(calc_width);
        }
        

        console.log($(".profile-cover-picture-preview").height());
        console.log($(".profile-cover-picture-preview").width());
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

        let max_height = 500;

        let height = $(".profile-picture-preview").height();
        let width = $(".profile-picture-preview").width();

        let one_hundred_perc = height + width;

        let height_perc = height * 100 / one_hundred_perc;
        let width_perc = width * 100 / one_hundred_perc;
        
        if(height > width) {
            $(".profile-picture-preview").height($(".profile-picture-preview-container").height());
        } else {
            $(".profile-picture-preview").width($(".profile-picture-preview-container").width());
        }

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

/*
we stopped propagation because we don't need to hide the element when we click on them because, we can only hide them when we
click ooutside of them
*/
$(".profile-picture-preview, .profile-cover-picture-preview, #edit-profile-container, #cover-changer-container").click(function(event) {
    event.stopPropagation();
    
});

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

$("#change-cover-button, #change-picture-button").click(function(event) {
    
});

$("#private-account-button").click(function() {
    console.log("TET");
    if($("#private-account-state").val() == "1") {
        $("#private-account-button").css("backgroundImage", "url(assets/images/icons/off.png)");
        $("#private-account-state").val("-1");
    } else {
        $(this).css("backgroundImage", "url(assets/images/icons/on.png)");
        $("#private-account-state").val("1");
    }
});

//$("#edit-sub-container").height($("#edit-sub-container").parent().height() - $("#edit-profile-header").height());
