
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

let discussion_chat_opened = false;
let message_writing_notifier = 0;

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
});

$(".friend-search-input").on("change paste keyup", function() {
    let username = $(this).val();
    $.ajax({
        url: root + "layouts/chat/get_chat_friend_by_username.php",
        type: 'POST',
        data: {
            username: username
        },
        success(data) {
            $("#friends-chat-container").html("");
            $("#friends-chat-container").append(data);
            
            $(".friends-chat-item").click(function() {
                open_friend_chat_section($(this));
                console.log("chat friend opened from search section !");
                return false;
            });
        }
    });
 });

if(urlParams.get('username')) {
    var values = {
        'sender': null,
        'receiver': null
    };
    
    // First we get the current user and we set it to value["sender"] to use it later
    $.ajax({
        type: "GET",
        url: root + "security/get_current_user.php",
        success: function(current_user) {
            values["sender"] = current_user["id"];
            /*
                Then we fetch the user from username in the url query string using and past it to values["receiver"] to use it later
            */
            $.ajax({
                type: "GET",
                url: root + "api/user/get_by_username.php?username=" + urlParams.get('username'),
                success: function(response) {
                    if(response["success"]) {
                        values["receiver"] = response["user"]["id"];
                        // If the user fetched successfully we generate a chat section based on the two values in values array(sender and receiver)

                        let url = root + "layouts/chat/generate_chat_container.php";
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: values,
                            success: function(data) {
                                $("#no-discussion-yet").remove();
                                $("#chat-global-container").append(data);
                                
                                $("#chat-container").height($(window).height() - 200); // 200 = 116 + 24(12 padding top and 12 padding bottom) + 60 (height of message text input)
                    
                                // Here we bring every message between the sender and user
                                $.ajax({
                                    type: 'POST',
                                    url: root + 'api/messages/get_friend_messages.php',
                                    data: values,
                                    success: function(data) {
                                        $("#chat-container").append(data);
                                        handle_message_elements_events($(".message-global-container"));
                                        // Scroll to the last message
                                        $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));
                                    }
                                })

                                // Here we also call the api to fill in messages to chat container
                                $("#send-message-button").click(function() {
                                    let chat_text_content = $('#second-chat-part').find("#chat-text-input").val();
                                    send_message(values["sender"], values["receiver"], chat_text_content);
                                });


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

$(".friends-chat-item, .friend-chat-discussion-item-wraper").click(function() {
    open_friend_chat_section($(this));
    console.log("chat friend opened !");
    return false;
});

$(document).keypress(function(e) {
    let message_input = $('#second-chat-part').find("#chat-text-input");
    let isFocused = (document.activeElement === message_input[0]);
    
    let sender = $("#second-chat-part").find(".chat-sender").val();
    let receiver = $("#second-chat-part").find(".chat-receiver").val();
    let text_data = message_input.val();

    if(isFocused && e.keyCode == 13) {
        send_message(sender, receiver, text_data);
    }
});

function send_message(sender, receiver, text_data) {
    save_data_and_return_compoent(sender, receiver, text_data, function(result) {
        if(result) {
            let values = {
                "sender": sender,
                "receiver": receiver
            };

            $("#chat-container").append(result);

            /*
                The following code handle the message when appear by adding some events to elements
            */
            $('#second-chat-part').find("#chat-text-input").val("");

            message_writing_notifier = 0;
            $.ajax({
                type: "POST",
                url: root + "api/messages/message_writing_notifier/delete.php",
                data: values
            });
            
            handle_message_elements_events($(".message-global-container").last());

            // Scroll to the last message
            $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));

            $(".reply-container").css("display", "none");
            $("#chat-text-input").attr("placeholder", "Type a new message");
            $("#chat-text-input").css("paddingLeft", "40px");
            $("#chat-text-input").focus();
        }
    });
}


function save_data_and_return_compoent(sender, receiver, message, handle_data) {
    /*
        Remember that ajax function is asynchronous
    */

    var values = {
        'sender': sender,
        'receiver': receiver,
        'message': message
    };

    if($(".reply-container").css("display") != "none") {
        values['is_reply'] = 'yes',
        values['replied_message_id'] = $(".reply-container").parent().parent().find(".replied-message-id").val();
    }

    $.ajax({
        type: "POST",
        url: root + "api/messages/Send.php",
        data: values,
        success: function(data) {
            handle_data(data);
        }
    });
}

function handle_message_elements_events(element) {

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

    element.find(".chat-message-more-button").on( {
        click: function(event) {
            event.stopPropagation();
            event.preventDefault();

            let container = $(this).parent().parent().find(".sub-options-container");
            if(container.css("display") == "none") {
                // Close any message suboption container in the chat section, then display the clickable button suboption
                $("#chat-container").find(".sub-options-container").css("display", "none");
                container.css("display", 'block');
            } else
                container.css("display", 'none');
        
            return false;
        }
    });

    element.find(".delete-current-user-message").click(function() {
        console.log("delete");
        let message_id = $(this).parent().find(".message_id").val();
        let message_container = $(this);
        while(!message_container.hasClass("message-global-container")) {
            message_container = message_container.parent();
        }
        
        $.ajax({
            url: root + 'api/messages/DELETE.php',
            type: 'POST',
            data: {
                'message_id': message_id,
                'is_received': 'no'
            },
            success: function(response) {
                message_container.remove();
            }
        });

        return false;
    });

    element.find(".delete-received-message").click(function() {
        
        let message_container = $(this);
        while(!message_container.hasClass("message-global-container")) {
            message_container = message_container.parent();
        }
        
        let message_id = null;
        if(message_container.hasClass("romrc")) {
            message_id = message_container.find(".message_id").val();
        } else {
            message_id = $(this).parent().find(".message_id").val();
        }
        
        $.ajax({
            url: root + 'api/messages/DELETE.php',
            type: 'POST',
            data: {
                'message_id': message_id,
                'is_received': 'yes'
            },
            success: function(response) {
                message_container.remove();
            }
        });
        console.log(message_container);

        return false;
    });

    element.find(".reply-button").click(function() {
        
        let message = '';
        let message_id = '';
        let global_container = $(this);
        while(!global_container.hasClass("message-global-container")) {
            global_container = global_container.parent();
        }
        
        if(global_container.hasClass("romrc")) {
            message_id = $(this).parent().find(".message_id").val();
            message = global_container.find(".received_replied_message_text").text();
            if(message.length > 12) {
                message = message.substring(0, 11) + " ..";
            }
    
            console.log("reply message text: " + ", id: " + message_id);
        } else {
            message_id = $(this).parent().find(".message_id").val();
            message = global_container.find(".message-text").text();

            if(message.length > 12) {
                message = message.substring(0, 11) + " ..";
            }

            console.log("normal message text: " + message + ", id: " + message_id);

        }

        $(".reply-container").find(".message-text-rep").text(message);
        $(".reply-container").find(".replied-message-id").val(message_id);
        $(".reply-container").css("display", "flex");
        $("#chat-text-input").attr("placeholder", "Reply ..");

        let padding_left = 2 + 30 + $(".reply-container").width();

        $("#chat-text-input").css("paddingLeft", padding_left);
        $("#chat-text-input").focus();

        return false;
    });

    $(".original-message-replied-container, .received-original-message-replied-container").click(function() {


        let original_message_id = $(this).find(".original_mid").val();
        
        let message_container = $(this).parent();

        let height_from_bottom_to_original = 0;
        
        /*
            We start from bottom to the original message and get heights of messages appended to height_from_bottom_to_original
            Then WE TAKE the height from bottom to the reply FROM the global height of chat container
        */

        message_container = $(".message-global-container").last();

        console.log(message_container)

        while(message_container.find(".message_id").val() != original_message_id) {
            height_from_bottom_to_original += message_container.height();
            message_container = message_container.prev();
        }

        let scroll_target = document.getElementById("chat-container").scrollHeight - height_from_bottom_to_original;
        let pr = message_container.prev();
        if(pr != null) {
            scroll_target -= pr.height() + 8;
        }

        console.log(message_container);
        $("#chat-container").animate({
            scrollTop: scroll_target
        }, 2000, function() {
            message_container.animate({
                opacity: 0.6
            }, 300, function() {
                message_container.animate({
                    opacity: 1
                }, 300);
            })
        });
        console.log("hey this is done !");
    });
}

function handle_chat_container_elements_events() {
    $(".message-input-box").find("#close-reply-container").click(function() {
        $(this).parent().css("display", "none");
        $("#chat-text-input").attr("placeholder", "Type a new message");
        $("#chat-text-input").css("paddingLeft", "40px");
        $("#chat-text-input").focus();
        return false;
    });
}

let receiver_user_id = null;

// IMPLEMENTING LONG POLLING TO CREATE A REAL TIME MESSAGE FETCHING MECHANISM
function waitForMessages() {
    let url = root + "server/long-polling.php";
    let values = {
        "receiver": $("#second-chat-part").find(".chat-receiver").val()
    }
    $.ajax({
        url: url,
        type: "POST",
        data: values,
        success: function(response) {
            console.log("get a message !");
            notification_sound_play();
            $("#chat-container").append(response);
            handle_message_elements_events($(".message-global-container").last());
            // Scroll to the last message
            $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));
            waitForMessages();
        }
    });
}

function track_message_writing() {
    let url = root + "server/message_writing_notifier.php";
    let values = {
        "receiver": $("#second-chat-part").find(".chat-receiver").val()
    }
    $.ajax({
        url: url,
        type: "POST",
        data: values,
        success: function(response) {
            if(response["finished"]) {
                $(".message_writing_notifier_text").css("display", "none");
            } else {
                $(".message_writing_notifier_text").css("display", "block");
            }

            track_message_writing();
        }
    });
}

function notification_sound_play() {
    let audio = new Audio(root+'public/assets/audios/tone.mp3');
    audio.play();
}

function open_friend_chat_section($element) {
    if($element.hasClass("friends-chat-item")) {
        $(".friend-chat-discussion-item-wraper").find(".selected-chat-discussion").css("display", "none");
    }
    let captured_id = $element.find(".receiver").val();
    let current_id = $element.find(".sender").val();
    var values = {
        'sender': current_id,
        'receiver': captured_id
    };

    let url = root + "layouts/chat/generate_chat_container.php";

    if(discussion_chat_opened) {
        $("#second-chat-part").remove();
    }

    $.ajax({
        type: "POST",
        url: url,
        data: values,
        success: function(data) {
            $("#no-discussion-yet").remove();
            $("#chat-global-container").append(data);
            
            handle_chat_container_elements_events();
            
            $("#chat-container").height($(window).height() - 200); // 200 = 116 + 24(12 padding top and 12 padding bottom) + 60 (height of message text input)
            // Here we bring every message between the sender and user
            $.ajax({
                type: 'POST',
                url: root + 'api/messages/get_friend_messages.php',
                data: values,
                success: function(data) {
                    $("#chat-container").append(data);
                    handle_message_elements_events($(".message-global-container"));
                    // Scroll to the last message
                    $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));

                    // --------------- Update the receiver_user_id used for long-polling purpose ---------------------
                    receiver_user_id = $element.find(".receiver").val();
                    waitForMessages();
                    track_message_writing();
                }
            });

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

                        $('#second-chat-part').find("#chat-text-input").val("");
                        message_writing_notifier = 0;
                        $.ajax({
                            type: "POST",
                            url: root + "api/messages/message_writing_notifier/delete.php",
                            data: values,
                            success: function(data) {
                                console.log("Notification deleted !");
                            }
                        });
                        
                        handle_message_elements_events($(".message-global-container").last());

                        // Scroll to the last message
                        $("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));
                    }
                });
            });

            message_writing_notifier = 0;
            // Display user's writing a message when a friend is writing a message
            $('#second-chat-part').find("#chat-text-input").on({
                input: function() {
                    if(!message_writing_notifier) {
                        $.ajax({
                            type: "POST",
                            url: root + "api/messages/message_writing_notifier/add.php",
                            data: values,
                            success: function(data) {
                                console.log("Notification registered !");
                            }
                        });

                        message_writing_notifier++;
                    }
                }
            })

            $('#second-chat-part').find("#chat-text-input").keyup(function() {
                if(!this.value) {
                    message_writing_notifier = 0;
                    $.ajax({
                        type: "POST",
                        url: root + "api/messages/message_writing_notifier/delete.php",
                        data: values,
                        success: function(data) {
                            message_writing_notifier = 0;
                            console.log("Notification deleted !");
                        }
                    });
                }
            
            });

            discussion_chat_opened = true;
        }
    });
}

$(".refresh-discussion").click(function() {
    
    $.ajax({
        url: root + "security/get_current_user.php",
        success: function(data) {
            let current_user_id = data["id"];

            $.ajax({
                url: root + "layouts/chat/discussions/get_user_discussions.php",
                type: 'POST',
                data: {
                    'user_id': current_user_id
                },
                success: function(response) {
                    $("#friend-chat-discussions-container").html("");
                    $("#friend-chat-discussions-container").append(response);
                }
            })
        }
    })

    return false;
});

window.onresize = function() {
    $("#second-chat-part").height($(window).height() - 55);
    $("#first-chat-part").height($(window).height() - 55);

    $("#friends-chat-container").height($(window).height() - 402);
}
