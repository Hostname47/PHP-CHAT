
let headerHeight = 55;

$("#master-right").height($(window).height() - headerHeight - 4);
$("#master-left").height($(window).height() - headerHeight - 4);
$("#contacts-container").height($("#master-right").height() - 40);

$("#contact-search").click(function() {
    let contact_search_container = $("#contact-search-field-container");
    if(contact_search_container.css("width") != "230px") {
        contact_search_container.animate({
            width: "230px"
        }, 300);

    } else {
        contact_search_container.animate({
            width: "0px"
        }, 300);
    }

    return false;
});

$("#close-contact-search").click(function() {
    $("#contact-search-field-container").animate({
        width: "0px"
    }, 300);
    return false;
})

$(".post-to-option").click(function() {
    $(this).parent().find("input[name='post-to']").prop("checked", false);
    $(this).find("input").prop("checked", true);
});