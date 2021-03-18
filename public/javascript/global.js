$(".header-profile-edit-button").click(function() {
    window.location.href = root + "profile.php?edit";
    
    return false;
})

$(".delete-message-hint").click(function() {
    $(this).parent().css("display", "none")
})

$("#addd").click(function() {
    $.ajax({
        type: 'post',
        url: root + "layouts/post/generate_post.php",
        data: {
            "post_id": 40
        },
        success: function(component) {
            $("#posts-container").prepend(component);
        }
    })

    return false;
});

let headerHeight = 55;

function adjust_left_right_containers() {
    $("#master-right").height($(window).height() - headerHeight - 4);
    $("#master-left").height($(window).height() - headerHeight - 7);
    $("#master-left-container").height($("#master-left").height() - 84);
    $("#contacts-container").height($("#master-right").height() - 40);
}

adjust_left_right_containers();

$(window).resize(function() {
    adjust_left_right_containers();
})

$(".button-with-suboption").click(function() {
    let container = $(this).parent().find(".sub-options-container");
    if(container.css("display") == "none") {
        $(".sub-options-container").css("display", "none");
        container.css("display", "block");
    } else {
        container.css("display", "none");
    }
    return false;
});

/* When the user click somewhere in bofy section all absolute containers will disappear except if the section is a
header container itself */
/*$("body").click(function(evt) {
    $(".sub-options-container").css("display", "none");
});*/

document.addEventListener("click", function(event) {
    $(".sub-options-container").css("display", "none");
}, false);

// we prevent
let subContainers = document.querySelectorAll('.sub-options-container');
for(let i = 0;i<subContainers.length;i++) {
    subContainers[i].addEventListener("click", function(evt) {
        $(this).css("display", "block");
        evt.stopPropagation();
    }, false);
}

$(".post-to-option").click(function() {
    if(!$(this).find(".rad-opt").is(":disabled")) {
        $(this).parent().find("input[name='post-to']").prop("checked", false);
        $(this).find("input").prop("checked", true);
    }
});

/*
    REMEMBER: Any relational operation between users need a button with appropriate class like add-user or unfriend and 
    along with that button you should provide two important inputs: current_user_id which point to the user that is currently
    logged in, and current_profile_id which point to the target user.
    The action button and the two fields need to be inside a form regardless on their DOM depth
*/

/*
    IMPORTANT: what happens when user click follow button ?
    -> First we check if the user who click the button is the same as the user who is currently logged in by sending the user_id
       to the check file within 'functions/security/check_current_user.php' to check the user, Then we want to make follow
       add as the default behaviour of follow button, If the user who clicks follow is not following the followed user
       we add the follow record and the user follow him successfully. In the other hand if the user is already follow
       him we know that from the response of add api file by checking success array element if the success is return false
       meaning iether the id is not valide or there's already a record with these 2 ids meaning the current user is already
       follow this guy
       In case of failure we asume that there's already follow between the two, so we call delete api file to delete the record
       Note: Notice when a user unfollow another user, that doesn't mean the later one unfollow the first one because the 
       following record only describe one way follow (if A follows B and B follows A and later B unfollow A; A still follows B)
*/

$(".follow-button").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let followButton = $(this);
    let form = $(this).parent();

    /*
        We do that because some inputs are not directly child of the form so when followButton get clicked we need to start from this button
        to the first form ancestor and use it as form because in friend sub-option we have an inut inside div which is not a direct
        child of follow-form
    */
    while(form.prop("tagName") != "FORM") {
        form = form.parent();
    }

    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add follow record (This basically add some layer of security)
                url = root + "api/follow/add.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["success"]) {
                            followButton.removeClass("follow-user");
                            followButton.attr("value", "Unfollow");

                            if($(".follow-label")) {
                                $(".follow-label").text("Unfollow");
                            }

                            if($(".follow-menu-header-form").find(".follow-button")) {
                                $(".follow-menu-header-form").find(".follow-button").removeClass("follow-user");
                                $(".follow-menu-header-form").find(".follow-button").addClass("followed-user");
                                $(".follow-menu-header-form").find(".follow-button").attr("value", "Followed");
                            }
                        } else {
                            url = root + "api/follow/delete.php";
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: form.serialize(),
                                success: function() {
                                    followButton.removeClass("followed-user");
                                    followButton.attr("value", "Follow");

                                    if($(".follow-label")) {
                                        $(".follow-label").text("Follow");
                                    }

                                    if($(".follow-menu-header-form").find(".follow-button")) {
                                        $(".follow-menu-header-form").find(".follow-button").removeClass("followed-user");
                                        $(".follow-menu-header-form").find(".follow-button").addClass("follow-user");
                                        $(".follow-menu-header-form").find(".follow-button").attr("value", "Follow");
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
});

$(".add-user").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let addButton = $(this);
    let form = $(this).parent();
    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add user relation record (This basically add some layer of security)
                url = root + "api/user_relation/send_request.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["error"]) {
                            // Here add code to display error div to user or something
                        }
                        else if(response["success"]) {
                            addButton.attr("value", "Cancel Request");
                        } else {
                            url = root + "api/user_relation/cancel_request.php";
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: form.serialize(),
                                success: function() {
                                    addButton.attr("value", "Add");
                                    addButton.removeClass("unfriend-white-back");
                                    addButton.addClass("add-user-back");
                                }
                            });
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
})

$(".unfriend").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let unfriend = $(this);
    let form = unfriend.parent();

    /*
        We do that because some inputs are not directly child of the form so when followButton get clicked we need to start from this button
        to the first form ancestor and use it as form because in friend sub-option we have an inut inside div which is not a direct
        child of follow-form
    */
    while(form.prop("tagName") != "FORM") {
        form = form.parent();
    }

    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add follow record (This basically add some layer of security)
                url = root + "api/user_relation/unfriend_relation.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["success"]) {
                            location.reload();
                        } else {
                            console.log("Open console to see API request response !");
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
})

$(".accept-user").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let unfriend = $(this);
    let form = unfriend.parent();

    /*
        We do that because some inputs are not directly child of the form so when followButton get clicked we need to start from this button
        to the first form ancestor and use it as form because in friend sub-option we have an inut inside div which is not a direct
        child of follow-form
    */
    while(form.prop("tagName") != "FORM") {
        form = form.parent();
    }

    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add follow record (This basically add some layer of security)
                url = root + "api/user_relation/accept_request.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["success"]) {
                            location.reload();
                        } else {
                            console.log("Open console to see API request response !");
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
});

$(".decline-user").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let unfriend = $(this);
    let form = unfriend.parent();

    /*
        We do that because some inputs are not directly child of the form so when followButton get clicked we need to start from this button
        to the first form ancestor and use it as form because in friend sub-option we have an inut inside div which is not a direct
        child of follow-form
    */
    while(form.prop("tagName") != "FORM") {
        form = form.parent();
    }

    let url = root + 'security/check_current_user.php';
    
    // First we check if the current user is valid
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response)
        {
            if(response) {
                // If the current user id is valide the we can add follow record (This basically add some layer of security)
                url = root + "api/user_relation/decline_request.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response)
                    {
                        if(response["success"]) {
                            location.reload();
                        } else {
                            console.log("Open console to see API request response !");
                        }
                    }
                });
            } else {
                console.log("ID changed ! error.");
            }
        }
    });
});

$(".accept-request").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let profile_path = $(this).parent().parent().parent().find('.link-to-profile').attr('href');
    let uid = $(this).parent().find(".uid").val();
  
    url = root + "api/user_relation/accept_request.php";
    $.ajax({
        type: "POST",
        url: url,
        data: {
            current_user_id: current_user_id,
            current_profile_id: uid
        },
        success: function(response)
        {
            if(response["success"]) {
                window.location.href = profile_path;
            } else {
                console.log("Open console to see API request response !");
            }
        }
    });   
})
$(".delete-request").click(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let profile_path = $(this).parent().parent().parent().find('.link-to-profile').attr('href');
    let uid = $(this).parent().find(".uid").val();
  
    url = root + "api/user_relation/decline_request.php";
    $.ajax({
        type: "POST",
        url: url,
        data: {
            current_user_id: current_user_id,
            current_profile_id: uid
        },
        success: function(response)
        {
            if(response["success"]) {
                window.location.href = profile_path;
            } else {
                console.log("Open console to see API request response !");
            }
        }
    });   
})
