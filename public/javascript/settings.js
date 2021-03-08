
$(document).ready(function() {
    if($("#private-account-state").val() == "1") {
        $("#private-account-button").css("backgroundImage", "url('"+root+"public/assets/images/icons/on.png')");
        $("#private-account-status").text("(ON)");
    } else {
        $("#private-account-button").css("backgroundImage", "url('" + root+"public/assets/images/icons/off-white.png')");
        $("#private-account-status").text("(OFF)");
    }
});


$(".button-with-suboption").click(function() {
    let contains = $(this).find(".button-subotions-container").length > 0;

    if(contains) {
        if($(this).find(".button-subotions-container").css("display") == "none") {
            $(this).find(".button-subotions-container").css("display", "block");
            let back = root+"public/assets/images/icons/down-arrow.png";
            $(this).find(".has-suboption").css("backgroundImage","url('"+back+"')");
        }
        else {
            let back = root+"public/assets/images/icons/right-arrow.png";
            $(this).find(".has-suboption").css("backgroundImage","url('"+back+"')");
            $(this).find(".button-subotions-container").css("display", "none");
        }
        return false;
    }
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
        $("#private-account-button").css("backgroundImage", "url('" + root+"public/assets/images/icons/off-white.png')");
        $("#private-account-status").text("(OFF)");
        $("#private-account-state").val('-1');
    } else {
        $("#private-account-button").css("backgroundImage", "url('"+root+"public/assets/images/icons/on.png')");
        $("#private-account-status").text("(ON)");
        $("#private-account-state").val('1');
    }
});

$("#cover-input").change(function(event) {
    if (this.files && this.files[0]) {
        let cover = $(".setting-cover").get(0);
        let reader = new FileReader();

        reader.onload = function(){
            cover.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
});

$("#avatar-input").change(function(event) {
    if (this.files && this.files[0]) {
        let picture = $("#setting-picture").get(0);
        let reader = new FileReader();

        reader.onload = function(){
            picture.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
});

function imageIsLoaded(e) {
    $('#myImg').attr('src', e.target.result);
    $('#yourImage').attr('src', e.target.result);
};

$(".logout-button").click(function() {
    $("#logout-form").submit();
});