<?php

require_once("dbconnect.php");

class Member {
    
    public $SQLConnection;

    function getId($member){
        $this->SQLConnection = Database::connect();
        //print_r($this->SQLConnection);

        $query = "SELECT id FROM cotgchat.members WHERE email = ?";

        $stmt = $this->SQLConnection->prepare($query);

        $stmt->bind_param('s', $member);
        
        $stmt->execute();

        $stmtResult = $stmt->get_result();

        $results = [];

        while ($result = $stmtResult->fetch_assoc()){
            array_push($results, $result);
        }

        return $results[0]['id'];
    }

    function searchMember($member){
        
    }
}

?> 