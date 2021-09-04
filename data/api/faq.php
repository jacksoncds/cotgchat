<?php

class FAQ{

    function get(){
        require_once('dbconnect.php');
        $SQLConnection = Database::Connect();

        $query = "SELECT * FROM cotgchat.faq";

        $stmt = $SQLConnection->prepare($query);
        
        $stmt->execute();

        $stmtResult = $stmt->get_result();

        $results = [];

        while ($result = $stmtResult->fetch_assoc()){
            array_push($results, $result);
        }

        $stmt->close();
        $SQLConnection->close();

        return $results;
    }
}

?>