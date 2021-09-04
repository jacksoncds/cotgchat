<?php

class Chat {
    private $_SQLConnection;
    
    public function __construct(){
        require_once('dbconnect.php');
        $this->_SQLConnection = Database::Connect();
    }

    function validateAuth($key, $chatId, $username){

    }

    function createChat($name, $alliances, $world, $timezone, $price, $userColor){
        $query = "INSERT INTO `cotgchat`.`chats`(
            `name`,`alliances`,`world`,`timezone`,`memberId`,`price`,`userColor`)
            VALUES (?,?,?,?,?,?,?)";

        //Get UserId by Session['username']
        require_once('member.php');
        session_start();
        $member = new Member();
        $memberId = $member->getId($_SESSION['username']);

        $stmt = $this->_SQLConnection->prepare($query);

        $stmt->bind_param('ssisiii', $name, $alliances, $world, $timezone, $memberId, $price, $userColor);
    
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function deleteChat(){

    }

    function addUserToChat($chatdId, $userId){
        $query = "INSERT INTO `cotgchat`.`user_chat_ref`(`chatId`, `userId`) VALUES (?,?)";

        $stmt = $this->_SQLConnection->prepare($query);

        $stmt->bind_param('ii', $chatId, $userId);
    
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function insertChatEntry($chatId, $userId, $message){
        $query = "INSERT INTO `cotgchat`.`chat_entry`(`chatId`,`userId`,`message`)
                    VALUES (?,?,?);";

                    $chatId = 1;
                    $userId = 1;

        $stmt = $this->_SQLConnection->prepare($query);

        $stmt->bind_param('iis', $chatId, $userId, $message);
    
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function getChat(){
        $query = "SELECT TOP 12 FROM cotgchat.chat_entry WHERE chatId = ? LIMIT 100";
        $chatId = 1;

        $stmt = $this->_SQLConnection->prepare($query);

        $stmt->bind_param('i', $chatId);
        
        $stmt->execute();

        $stmtResult = $stmt->get_result();

        $results = [];

        while ($result = $stmtResult->fetch_assoc()){
            array_push($results, $result);
        }

        print_r($results);
        
    }

    function getChatsByMember($memberId){
        $query = "SELECT * FROM chats where memberId = ?";

        $stmt = $this->_SQLConnection->prepare($query);

        $stmt->bind_param('i', $memberId);
        
        $stmt->execute();

        $stmtResult = $stmt->get_result();

        $results = [];

        while ($result = $stmtResult->fetch_assoc()){
            array_push($results, $result);
        }

        return $results;
    }

    function getUsersByChatId($chatId){
        $query = "SELECT name 
                    FROM users 
                    LEFT JOIN
                    user_chat_ref 
                    ON 
                        users.id = user_chat_ref.userId 
                    WHERE chatId = ?";

        $stmt = $this->_SQLConnection->prepare($query);

        $stmt->bind_param('i', $chatId);
        
        $stmt->execute();

        $stmtResult = $stmt->get_result();

        $results = [];

        while ($result = $stmtResult->fetch_assoc()){
            array_push($results, $result);
        }

        return $results;
    }

}

?>