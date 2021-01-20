
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

$(".user-info-section-link").on( {
    mouseenter: function() {
        $(this).find("div p").css("textDecoration", "underline");
    },
    mouseleave: function() {
        $(this).find("div p").css("textDecoration", "none");
    }
});