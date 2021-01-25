
const urlParams = new URLSearchParams(window.location.search);

$("#second-chat-part").height($(window).height() - 55);
$("#first-chat-part").height($(window).height() - 55);

/*
    Why exactly 402: well that's because we have 55px which is height of header, 60px for discussion search header
    we have 3 discussion items each one has height of 50px + 24px foreach padding item(12px top + 12px bottom), 
    and friend chat search header which has height of 60px + (22px padding top and bottom)
    => 55px + 60px + 3*50px (+ 3*24px padding top=12 and padding bottom=12) + 60px
    => 55 + 60 + 150 + 72 + 60 + 22 = 397
    Note: HHH Remember we add border-bottom to discussion items :) and also to headers look at search we add a gray 
    border in bottom and right sides and because we have 2 search header we need to add 2
    => 55 + 60 + 150 + 72 + 60 + 22 = 397 + 3 + 2 = 402

    Hint: Now we set the whole discussion container height so that you can easily take the height of discussion off from height od document to get friends container height left
*/
$("#friends-chat-container").height($(window).height() - 402);

// Scroll to the last message
$("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));

$(".friend-chat-discussion-item-wraper").click(function() {
    $(".friend-chat-discussion-item-wraper").find(".selected-chat-discussion").css("display", "none");
    $(this).find(".selected-chat-discussion").css("display", "block");
})

$(".chat-message-more-button").click(function() {
    let container = $(this).parent().parent().find(".sub-options-container");
    if(container.css("display") == "none") {
        // Close any message suboption container in the chat section, then display the clickable button suboption
        $("#chat-container").find(".sub-options-container").css("display", "none");
        container.css("display", 'block');
    } else
        container.css("display", 'none');

    return false;
});

$(".new-message-button").click(function() {
    $("#styled-border").css("display","block");
    $("#styled-border").animate({
        opacity: '1'
    }, 600, function() {
        window.setTimeout(function() {
            $("#styled-border").animate({
                opacity: '0'
            }, 600, function() {
                $("#styled-border").css("display","none");
            });
        }, 600);
    });
    return false;
})

if(urlParams.get('username')) {
    var values = {
        'sender': null,
        'receiver': null
    };
    
    $.ajax({
        type: "GET",
        url: root + "security/get_current_user.php",
        success: function(current_user) {
            values["sender"] = current_user["id"];

            $.ajax({
                type: "GET",
                url: root + "api/user/get_by_username.php?username=" + urlParams.get('username'),
                success: function(response) {
                    if(response["success"]) {
                        values["receiver"] = response["user"]["id"];

                        let url = root + "view/chat/generate_chat_container.php";
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: values,
                            success: function(data) {
                                $("#no-discussion-yet").remove();
                                $("#chat-global-container").append(data);
                                
                                $("#chat-container").height($(window).height() - 200); // 200 = 116 + 24(12 padding top and 12 padding bottom) + 60 (height of message text input)
                    
                                // Here we also call the api to fill in messages to chat container

                                discussion_chat_opened = true;
                            }
                        });
                    } else {
                        console.log("user fetch failed !");
                    }
                }
            });
        }
    });
}

let no_discussion_chat_opened = false;

$(".friends-chat-item").click(function() {

    let captured_id = $(this).find(".receiver").val();
    let current_id = $(this).find(".sender").val();
    var values = {
        'sender': current_id,
        'receiver': captured_id
    };

    let url = root + "view/chat/generate_chat_container.php";

    if(!no_discussion_chat_opened) {
        $("#second-chat-part").remove();
    }

    $.ajax({
        type: "POST",
        url: url,
        data: values,
        success: function(data) {
            $("#no-discussion-yet").remove();
            $("#chat-global-container").append(data);
            
            $("#chat-container").height($(window).height() - 200); // 200 = 116 + 24(12 padding top and 12 padding bottom) + 60 (height of message text input)

            // Here we bring every message between the sender and user

            $("#send-message-button").click(function() {
                let chat_text_content = $('#second-chat-part').find("#chat-text-input").val();
                let chat_values = values;
                // Append message content to values passed to the api
                chat_values.message = chat_text_content;
                $.ajax({
                    type: "POST",
                    url: root + "api/messages/Send.php",
                    data: values,
                    success: function(data) {
                        $("#chat-container").append(data);
                        $('#second-chat-part').find("#chat-text-input").val("")
                        
                        $(".message-global-container").on({
                            mouseenter: function() {
                                $(this).find(".chat-message-more-button").css("display", "block");
                                $(this).find(".message-date").css("display", "block");
                            },
                            mouseleave: function() {
                                $(this).find(".chat-message-more-button").css("display", "none");
                                $(this).find(".message-date").css("display", "none");
                            }
                        });
                    }
                });

                //$("#second-chat-part")
            });

            discussion_chat_opened = true;
        }
    });


    return false;
});


