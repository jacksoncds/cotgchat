<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, Authorization, X-Requested-With, Access-Control-Request-Method, Access-Control-Request-Headers, pp-ss, Content-Encoding');

require_once(__DIR__.'/../../data/api/chat.php');

function get(){
    $chat = new Chat();

    $chat->getChat();
}

function send($msg){
    $chat = new Chat();
    $chatId = 1;
    $userId = 1;
    $message = $_POST['message'];
    $chat->insertChatEntry($chatId, $userId, $message);
}

if(isset($_GET['key']) AND isset($_GET['chatid'])){
    get();
}

if(isset($_POST['key']) AND isset($_POST['chatid']) AND isset($_POST['message'])){
    send($_POST['message']);
}

?>