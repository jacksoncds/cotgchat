<?php

class Login
{
    private $_SQLLoginQuery = "SELECT `password` FROM `members` WHERE `email` = ?";
    private $_SQLConnection;
    private $_email = "";
    private $_password = "";

    public static $output = [];

    public function __construct($SQLConn, $email, $password)
    {
        $this->_SQLConnection = $SQLConn;
        $this->_email = $email;
        $this->_password = $password;

        $this->login();
    }

    public function __destruct()
    {
        $this->_SQLConnection->close();
    }

    public function setOutput($status, $message = '')
    {
        self::$output['status'] = $status;
        self::$output['message'] = $message;
    }

    private function checkPassword($pw, $hash)
    {
        if (password_verify($pw, $hash)) {
            return true;
        } else {
            return false;
        }
    }

    public function login()
    {
        $output = [];
        $results = [];

        $stmt = $this->_SQLConnection->prepare($this->_SQLLoginQuery);

        $stmt->bind_param('s', $this->_email);

        $stmt->execute();

        $stmtResult = $stmt->get_result();

        while ($result = $stmtResult->fetch_assoc()) {
            array_push($results, $result);
        }

        //Check if account exists
        if (sizeof($results) > 0) {
            //Account Exists

            if ($this->checkPassword($this->_password, $results[0]['password'])) {
                //Passwords is right, start username session
                $this->setOutput(0, "Logged in successfully.");

                $_SESSION['username'] = $this->_email;
            } else {
                //password is incorrect
                $this->setOutput(1, "Email or password is incorrect.");
            }

            //Close things up
            $stmt->close();
        } else {
            //"Cound not find that account.";
            $this->setOutput(2, "Cound not find that account or password.");
        }
    }
}

class Logout
{

    public static $logout = false;

    public function __construct()
    {
        $this->logout();
    }

    public static function logout()
    {
        if (isset($_SESSION['username'])) {
            session_unset($_SESSION['username']);
            self::$logout = true;
        }
    }
}

class Register
{
    private $_SQLCreateAccountQuery = "INSERT INTO `members` (`password`, `email`) VALUES (?,?)";
    private $_SQLLoginQuery = "SELECT `password` FROM `members` WHERE `email` = ?";
    private $_SQLConnection;
    private $_password = "";
    private $_email = "";

    public static $output = [];

    public function __construct($SQLConn, $password, $email)
    {
        $this->_SQLConnection = $SQLConn;
        $this->_password = $password;
        $this->_email = $email;

        $this->register();
    }

    public function __destruct()
    {
        $this->_SQLConnection->close();
    }

    public function setOutput($status, $message = '')
    {
        self::$output['status'] = $status;
        self::$output['message'] = $message;
    }

    public function updatePassword($email, $password)
    {
        $query = "UPDATE `members` SET `password`='$password'' WHERE `email` = '$email'";

        if ($this->_SQLConnection->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    private function fetchAssocArrayByQuery($query)
    {
        $stmt = $this->_SQLConn->prepare($query);
        $results = [];

        $stmt->execute();

        $stmtResult = $stmt->get_result();

        while ($result = $stmtResult->fetch_assoc()) {
            array_push($results, $result);
        }

        return $results;
    }

    public function hashPassword()
    {
        return password_hash($this->_password, PASSWORD_DEFAULT);
    }

    public function changePassword()
    {
        // Check if account exists.
        if (isAccount($this->_email) == true && passwordStrenth() == true) {

            // Account exist and password is good.
            $query = "SELECT `password` FROM `members` WHERE `email` = $this->_email";

            $result = fetchAssocArrayByQuery($query);

            $currentHash = hashPassword($result["password"]);

            $hash = hashPassword();

            if ($currentHash === $hash) {
                updatePassword($this->_email, $this->_password);
            }
        }
    }

    public function passwordStrenth()
    {
        require('config.php');
        $pw = $this->_password;

        // Check for password min and max length.
        if (
            strlen($pw) > $passwordStrenth["minLength"] and
            strlen($pw) < $passwordStrenth["maxLength"]
        ) {
            // Banned chars.
            for ($i = 0; $i < sizeof($passwordStrenth["specialCharsBanned"]); $i++) {
                if (strlen(strstr(strtolower($pw), $passwordStrenth["specialCharsBanned"][$i])) > 0) {
                    $this->setOutput(
                        'error',
                        "The character " . $passwordStrenth["specialCharsBanned"][$i] . " is not allowed."
                    );
                    return false;
                }
            }
        } else {
            $this->setOutput('error', 'Password does not meet minimum length.');
            return false;
        }
        // Password is good.
        $this->setOutput('ok');
        return true;
    }

    public function isAccount($email)
    {
        // Check if account was already created.
        $query = "SELECT `id` FROM `members` WHERE `email` ='$email'";

        $result = $this->_SQLConnection->query($query);

        if ($result->num_rows == 0) {
            $this->setOutput('ok');
            return false;
        } else {
            $this->setOutput('error', "An account with email: $email already exits.");
            return true;
        }
    }

    private function createAccount($hash)
    {
        $stmt = $this->_SQLConnection->prepare($this->_SQLCreateAccountQuery);

        $stmt->bind_param('ss', $hash, $this->_email);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function register()
    {
        //Check if account exists.
        if ($this->isAccount($this->_email) == false && $this->passwordStrenth() == true) {
            // Account does not exist and password is good.
            $hash = $this->hashPassword();

            $this->createAccount($hash);
        }
    }
}
?>