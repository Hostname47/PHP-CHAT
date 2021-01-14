

$(".search-result-person").click(function() {
    /*
    Handle person click to redirect user to the user clickable profile
    PLEASE Read [IMPORTANT#6]
    Now We only use the hidden id in the container
    */
    let id = $(this).find(".uid").attr("value");

    xmlhttprequest = new XMLHttpRequest();
    xmlhttprequest.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let result = JSON.parse(this.responseText);
            if(result["problem"] == undefined) {
                let username = result["username"];

                location.href = root + "profile.php?username="+username;
            } else {
                // Handle when the id is not present in database, or an invalid data is provided
            }
        }
    };
    xmlhttprequest.open("GET", root+"api/user/GET.php?id="+id, true);
    xmlhttprequest.send();

    return false;
});