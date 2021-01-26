$(".contact-user").on({
    mouseenter: function() {
        $(this).find(".contact-item-button").css("display", "block");
    },
    mouseleave: function() {
        $(this).find(".contact-item-button").css("display", "none");
    },
    click: function() {
        console.log("user button get clicked !");
    }
});

$(".contact-go-to-profile").click(function() {
    //window.location.href = root + "profile.php?username=grotto";

    let captured_id = $(this).parent().find(".uid").val();
    let current_id = $(this).parent().find(".current").val();
    console.log(captured_id);
    var values = {
        'uid': captured_id,
        'current_user_id': current_id
    };
    
    let url = root + "security/check_current_user.php";

    $.ajax({
        type: "POST",
        url: url,
        data: values,
        success: function(response) {
            url = root + "security/check_user_existence.php";
            if(response) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: values,
                    success: function(response) {
                        if(response) {
                            window.location.href = root + "profile.php?username=" + response["username"];
                        }
                    }
                });
            }
        }
    });

    return false;
});

$(".contact-go-to-chat").click(function() {
    //window.location.href = root + "profile.php?username=grotto";

    let captured_id = $(this).parent().find(".uid").val();
    let current_id = $(this).parent().find(".current").val();
    console.log(captured_id);
    var values = {
        'uid': captured_id,
        'current_user_id': current_id
    };
    
    let url = root + "security/check_current_user.php";

    $.ajax({
        type: "POST",
        url: url,
        data: values,
        success: function(response) {
            url = root + "security/check_user_existence.php";
            if(response) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: values,
                    success: function(response) {
                        if(response) {
                            window.location.href = root + "chat.php?username=" + response["username"];
                        }
                    }
                });
            }
        }
    });

    return false;
});

$(".contact-user").click(function() {
    //window.location.href = root + "profile.php?username=grotto";

    let captured_id = $(this).find(".uid").val();
    let current_id = $(this).find(".current").val();
    console.log(captured_id);
    var values = {
        'uid': captured_id,
        'current_user_id': current_id
    };
    
    let url = root + "security/check_current_user.php";

    $.ajax({
        type: "POST",
        url: url,
        data: values,
        success: function(response) {
            url = root + "security/check_user_existence.php";
            if(response) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: values,
                    success: function(response) {
                        if(response) {
                            window.location.href = root + "profile.php?username=" + response["username"];
                        }
                    }
                });
            }
        }
    });

    return false;
});