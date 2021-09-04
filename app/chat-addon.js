// ==UserScript==
// @name         
// @namespace    http://tampermonkey.net/
// @version      0.3
// @description  XChar client.
// @author       You
// @match        
// @grant        none
// ==/UserScript==

(function() {
    'use strict';


    var chatWindow = $.parseHTML("<div id='xallychat' style='word-wrap: break-word; display: block; position: absolute;top: 35px;height: calc(100% - 38px);width: 98%;font-size: 75%;padding: 0;margin-left: 1.5%;' aria-labelledby='ui-id-10' class='ui-tabs-panel ui-widget-content ui-corner-bottom' role='tabpanel' aria-hidden='false'> <div id='chatHolder'> <div id='chatDisplayw'></div><div id='chatOut'> <input type='text' name='chatMsg' id='wchatMsg' autocomplete='off'> <button id='wsendChat' class='greenb'>Send</button> </div></div></div>");

    var chatTab = "<li id='xchat' class='ui-state-default ui-corner-top' role='tab' tabindex='0' aria-controls='worldChat' aria-labelledby='ui-id-7' aria-selected='true' aria-expanded='true' style:'left:3%; background:#4B3A2D; font-size: 60%; margin-left: .2%; margin-right: .2%; margin-top: 1.6%; border-width: 1px 1px 0; border-color: #000;'><a href='#xallyChat' class='ui-tabs-anchor' role='presentation' tabindex='-1' id='ui-id-7'>XAlly</a></li>";
    
    $("#chatTabs").append(chatWindow);
    
    $("#chatTabsButtons").append(chatTab);
    
    //X ally chat
    $("#xchat").click(function(){
    
        $("#chat").removeClass('ui-state-active ui-tabs-active');
        $("#achat").removeClass('ui-state-active ui-tabs-active');
        $("#ochat").removeClass('ui-state-active ui-tabs-active');
        $("#wchat").removeClass('ui-state-active ui-tabs-active');
        
        $("#xallychat").show();
        
        //Hide chat boxes
        $("#worldChat #chatDisplay").hide();
        $("#allianceChat #chatDisplaya").hide();
        $("#officerChat #chatDisplayo").hide();
        $("#whisperChat #chatDisplayw").hide();
        
        $("#xchat").addClass('ui-state-active ui-tabs-active');
    });
    
    
    //x chatr send
    $("#xallychat #wsendChat").click(function(){
        console.log("sent");
    });

    $("#chat").click(function(){
    
        $("#xchat").removeClass('ui-state-active ui-tabs-active');
        $("#xallychat").hide();
        $("#chat").addClass('ui-state-active ui-tabs-active');
        $("#worldChat #chatDisplay").show();
        
    });
    
    $("#achat").click(function(){
    
        $("#xchat").removeClass('ui-state-active ui-tabs-active');
        $("#xallychat").hide();
        $("#achat").addClass('ui-state-active ui-tabs-active');
        $("#allianceChat #chatDisplaya").show();
    });
    
    $("#ochat").click(function(){
    
        $("#xchat").removeClass('ui-state-active ui-tabs-active');
        $("#xallychat").hide();
        $("#ochat").addClass('ui-state-active ui-tabs-active');
        $("#officerChat #chatDisplayo").show();
    });
    
    $("#wchat").click(function(){
    
        $("#xchat").removeClass('ui-state-active ui-tabs-active');
        $("#xallychat").hide();
        $("#wchat").addClass('ui-state-active ui-tabs-active');
        $("#whisperChat #chatDisplayw").show();
    });
    
    
    
    var settingsTab = $.parseHTML("<li id='xchattab' class='ui-state-default ui-corner-top' role='tab' tabindex='-1' aria-controls='gamegodxchat' aria-labelledby='ui-id-81' aria-selected='false' aria-expanded='false'><a href='#' class='ui-tabs-anchor' role='presentation' tabindex='-1' id='ui-id-81'>XChat</a></li>");
    var settingsPanel = $.parseHTML("<div id='gamegodxchat' aria-labelledby='ui-id-99' class='ui-tabs-panel ui-widget-content ui-corner-bottom' role='tabpanel' aria-hidden='false' style='display: none; font-size: 15px; padding:10px !important;'> <div id='gamegodxchat'><p class='smallredheading'>XChat Settings:</p><br /> <form class='ui-widget'><label for='xchat-key'>Key:</label><br /><input id='xchat-key' type='text' /><br /><label for='xchat-id'>Chat Id:</label><br /><input id='xchat-id' type='text' /><br /><br /><button id='xchat-save' class='greenb'>Save</button> </form>  </div> </div>");
    $("#goptionsTabs").append(settingsTab);
    $("#goptionsdiv").append(settingsPanel);
    
    $("#xchattab").click(function(){
        $("#goptionsTabs>li").removeClass('ui-tabs-active ui-state-active');
        $("#goptionsdiv .ui-tabs-panel").hide();
        $("#xchattab").addClass('ui-tabs-active ui-state-active');
        $("#gamegodxchat").show();
    });
    
    $("#goptionsTabs>li:not(#xchattab)").click(function(){
        $("#xchattab").removeClass('ui-tabs-active ui-state-active');
        $("#gamegodxchat").hide();
    });
    
    //Save settings
    $("#xchat-save").click(function(){
        var key = $("#xchat-key").val();
        var chatId = $("#xchat-id").val();
        localStorage.setItem("xchatKey", key);
        localStorage.setItem("xchatId", chatId);
    });
    
    $(document).ready(function(){

        console.log("ready");

    });
    
    function addToChat(jsonObj){
        var text = "<div style='overflow:hidden;'><span style='color:white'>" + jsonObj.time + " </span><span style='color:" + jsonObj.color + "' class='playerlink' data='" + jsonObj.player + "'>" + jsonObj.player + " :</span><span style='color:white'>" + jsonObj.message + "</span><br></div>";
        var element = $.parseHTML(text);
        
        $("#xallychat #chatDisplayw").append(element);
    }
    
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
    
    var obj = JSON.parse("{\"time\": \"22:23:38\",\"color\": \"#00FF00\",\"player\": \"jacksonbr\",\"message\": \"This is a test.\"}");
    
    addToChat(obj);
})();