<?php 

/*
 *	MySQL Settings.
*/
$mysqlDbAddress = "localhost";
$mysqlDbName = "cotgchat";
$mysqlUser = "mysqluser";
$mysqlPassword = "mysqlpassword";

/*
    Server domain
*/
$main_domain = "localhost";
$server_domain = "s1.$main_domain";


/*
    Password Strenth
*/
$passwordStrenth = [
    "minLength"                 => 5,
    "maxLength"                 => 32,
    "specialCharsBanned"        => ["'("],
    "capitalLetterRequirement"  => true,
];


// Server token expiration time in minutes.
$tokenExpiration = 5;

?>

