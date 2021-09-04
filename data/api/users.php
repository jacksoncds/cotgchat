<?php

class User {
    function create($name, $allianceName, $nameColor, $textColor){

        require_once('helper.php');
        require_once('alliance.php');
        require_once('dbconnect.php');

        $query = "INSERT INTO `cotgchat`.`users`
                    (`name`,
                    `cKey`,
                    `pColor`,
                    `mColor`,
                    `allianceId`
                    )
                VALUES
                    (?,
                    ?,
                    ?,
                    ?,
                    ?)";
        $SQLConnection = Database::connect();
        $stmt = $SQLConnection->prepare($query);

        $cKey = Helper::generateKey(32);

        $alliance = new Alliance();
        $allianceId = $alliance->getId($allianceName);

        $stmt->bind_param('ssssi', $name, $cKey, 
                                    $nameColor, $textColor,
                                    $allianceId);
    
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }

    }

    function getByChatId($id){
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