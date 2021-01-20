
$("#second-chat-part").height($(window).height() - 55);
$("#first-chat-part").height($(window).height() - 55);

$("#friend-chat-discussions-container").height($(window).height() - 116);

$("#chat-container").height($(window).height() - 200); // 200 = 116 + 24(12 padding top and 12 padding bottom) + 60 (height of message text input)

// Scroll to the last message
$("#chat-container").scrollTop($("#chat-container").prop("scrollHeight"));

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

$(".message-global-container").on({
    mouseenter: function() {
        $(this).find(".chat-message-more-button").css("display", "block");
        $(this).find(".message-date").css("display", "block");
    },
    mouseleave: function() {
        $(this).find(".chat-message-more-button").css("display", "none");
        $(this).find(".message-date").css("display", "none");
    }
})
