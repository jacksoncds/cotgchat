<?php

class Alliance {
    function getId($a){
        require_once('dbconnect.php');
        $SQLConnection = Database::connect();

        $query = "SELECT id FROM alliances WHERE name=?";

        $stmt = $SQLConnection->prepare($query);

        $stmt->bind_param('s', $a);
        
        $stmt->execute();

        $stmtResult = $stmt->get_result();

        $results = [];

        while ($result = $stmtResult->fetch_assoc()){
            array_push($results, $result);
        }

        print_r($results);
        
        return $results[0]['id'];
    }
}

?>