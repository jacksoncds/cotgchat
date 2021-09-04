<?php

class Auth {

    public function getImports(){
        require_once(__DIR__.'/../../data/api/account.php');
        require_once(__DIR__.'/../../data/api/dbconnect.php');
        require_once('helper_functions.php');
        
    }

    public function register(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $email_post = $request->email;
        $password_post = $request->password;
        $this->getImports();
        $sqlConnection = Database::connect();
        $password = HFtn::escapeInput($password_post, $sqlConnection);
        $email =    HFtn::escapeInput($email_post, $sqlConnection);
        $register = new Register($sqlConnection, $password, $email);
    }

    public function login(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $this->getImports();
        $sqlConnection = Database::connect();
        $password = HFtn::escapeInput($request->password, $sqlConnection);
        $email =    HFtn::escapeInput($request->email, $sqlConnection);
        $login = new Login($sqlConnection, $email, $password);
        HFtn::JSONFormatOutput($login::$output);
        
    }

    public function logout(){
        require_once(__DIR__.'/../../data/api/account.php');
        Logout::logout();
        if(Logout::$logout){
            HFtn::JSONFormatOutput("status:0");
        }
    }
}

?>