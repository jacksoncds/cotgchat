<?php

class ChatAuth {
    function __contruct(){
        
    }

    public $name = "";
    public $cKey = "";
    public $chatId = -1;

    function auth($data){
        require_once(__DIR__.'/../api/dbconnect.php');

        //Check chatId, name, cKey
        $authData = json_decode($data);

        $query = "  SELECT users.name, users.cKey, chats.id AS chatId
                        FROM users 
                    LEFT JOIN user_chat_ref 
                        ON users.id = user_chat_ref.userId 
                    LEFT JOIN chats 
                        ON user_chat_ref.chatId = chats.id 
                    WHERE users.name = ? 
                        AND chats.id = ? 
                        AND users.cKey = ?";

        
        $SQLConnection = Database::connect();

        $stmt = $SQLConnection->prepare($query);

        $stmt->bind_param('sis', $authData->name, $authData->chatid, $authData->key);
        
        $stmt->execute();

        $stmtResult = $stmt->get_result();

        while ($result = $stmtResult->fetch_assoc()){
            $this->name = $result['name'];
            $this->cKey = $result['cKey'];
            $this->chatId = $result['chatId'];
        }
        
        if($authData->name == $this->name && $authData->key == $this->cKey && $authData->chatid == $this->chatId){
            return true;
        } else {
            return false;
        }
    }
}

?>