
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

$("#logout-button").click(function() {
    $("#logout-form").submit();
    return false;
});


