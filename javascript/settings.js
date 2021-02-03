$(".button-with-suboption").click(function() {
    let contains = $(this).find(".button-subotions-container").length > 0;

    if(contains) {
        if($(this).find(".button-subotions-container").css("display") == "none") {
            $(this).find(".button-subotions-container").css("display", "block");
            let back = root+"assets/images/icons/down-arrow.png";
            $(this).find(".has-suboption").css("backgroundImage","url('"+back+"')");
            console.log("error in this back: " + back);
        }
        else {
            let back = root+"assets/images/icons/right-arrow.png";
            $(this).find(".has-suboption").css("backgroundImage","url('"+back+"')");
            $(this).find(".button-subotions-container").css("display", "none");
        }

    }

    return false;
});

$("#assets-wrapper").on({
    mouseenter: function() {
        $("#assets-wrapper").css("backgroundColor", "rgb(50,50,50)");
    },
    mouseleave: function() {
        $("#assets-wrapper").css("backgroundColor", "rgb(45,45,45)");
    }
});

$("#private-account-button").click(function() {
    let status = $("#private-account-state").val();

    if(status == 1) {
        $("#private-account-button").css("backgroundImage", "url('" + root+"assets/images/icons/off-white.png')");
        $("#private-account-status").text("(OFF)");
        $("#private-account-state").val('-1');
    } else {
        $("#private-account-button").css("backgroundImage", "url('"+root+"assets/images/icons/on.png')");
        $("#private-account-status").text("(ON)");
        $("#private-account-state").val('1');
    }
});