let root = "/CHAT/";

let current_user_id = null;

function handle_return(data) {
    current_user_id = data;
}

function get_current(handle_return) {
    $.ajax({
        url: root + 'security/get_current_user.php',
        type: 'get',
        success: function(current_user) {
            handle_return(current_user["id"]);
        }
    });
};
get_current(handle_return);

// Update the presence active every 2 minutes as long as the user logging in.
/*
    IMPORTANT: This file(config.js) should by in every page of the app, If this file is missed to be included in one of the
    files, then the activeness of the current user will not be updated and his friends will see him offline even if he's not
*/

var stillAlive = setInterval(function () {
    update_active_presence();
}, 120000);

function update_active_presence() {
    $.ajax({
        url: root + 'server/update_active_presence.php'
    });
}
