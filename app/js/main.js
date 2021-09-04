Date.prototype.getFormattedTime = function () {
    var hours = this.getHours() == 0 ? "12" : this.getHours() > 12 ? this.getHours() - 12 : this.getHours();
    var minutes = (this.getMinutes() < 10 ? "0" : "") + this.getMinutes();
    var seconds = (this.getSeconds() < 10 ? "0" : "") + this.getSeconds();
    var ampm = this.getHours() < 12 ? "AM" : "PM";
    var formattedTime = hours + ":" + minutes + ":" + seconds + " " + ampm;
    return formattedTime;
}

var __xKey = "x";
var __xChatId = "y";

function send(text){
    $.ajax({
        type: 'POST',
        url: 'http://localhost/cotgchat/chat/index.php',
        crossDomain: true,
        data: {message:text, key:__xKey, chatid:__xChatId},
        success: function(responseData, textStatus, jqXHR) {
            console.log(responseData);
        }
    });
}

function get(){
    $.ajax({
        type: 'GET',
        url: 'http://localhost/cotgchat/chat/index.php',
        crossDomain: true,
        data: {key:__xKey, chatid:__xChatId},
        success: function(responseData, textStatus, jqXHR) {
            console.log(responseData);
        }
    });
}

$(function() {
    $.material.init();
});