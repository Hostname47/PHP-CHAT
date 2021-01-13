
$(".profile-picture").css("borderColor", $("#first-section").css("backgroundColor"));


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

/*
we stopped propagation because we don't need to hide the element when we click on them because, we can only hide them when we
click ooutside of them
*/
$(".profile-picture-preview, .profile-cover-picture-preview, #edit-profile-container, #cover-changer-container").click(function(event) {
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

$(".follow-user").click(function(event) {
    event.preventDefault();

    let form = $(this).parent();
    let url = 'functions/security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(), // serializes the form's elements.
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add follow record (This basically add some layer of security)
                url = "api/follow/add.php";
                $.ajax({
                    type: "POST",
                    url: form.attr("action"), // api/follow/add.php
                    data: form.serialize(), // serializes the form's elements.
                    success: function(response)
                    {
                        
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });

})

//$("#edit-sub-container").height($("#edit-sub-container").parent().height() - $("#edit-profile-header").height());
