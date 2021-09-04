<?php

class HFtn {
    public static function escapeInput($obj, $sqlConnection){
        //Trim
        $obj = trim($obj);
        
        //html
        $obj = filter_var($obj, FILTER_SANITIZE_SPECIAL_CHARS);

        //mysql
        $obj = mysqli_real_escape_string($sqlConnection, $obj);
        return $obj;
    }

    public static function JSONFormatOutput($json){
        header("Content-type: application/json");
        echo json_encode($json); 
    }
}

?>