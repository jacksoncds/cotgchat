<?php

class Email {
    private static $_smtp_config = [
        'host' => "smtp server url",
        'port' => 465,
        'encryption' => 'ssl',
        'email' => 'email',
        'password' => 'password',
    ];

    private static $mailer = null;

    public static function default_config(){
        $dir = dirname(dirname(__FILE__));
        echo $dir;
        require_once("$dir/lib/swiftmailer/swift_required.php");

        $transport = Swift_SmtpTransport::newInstance()
            ->setHost(self::$_smtp_config['host'])
            ->setPort(self::$_smtp_config['port'])
            ->setEncryption(self::$_smtp_config['encryption'])
            ->setUsername(self::$_smtp_config['email'])
            ->setPassword(self::$_smtp_config['password']);

        self::$mailer = Swift_Mailer::newInstance($transport);
    }

    public static function auth($email, $password){
        require_once("../lib/swiftmailer/swiftmailer_required.php");

        $transport = Swift_SmtpTransport::newInstance()
            ->setHost($this->_smtp_config['host'])
            ->setPort($this->_smtp_config['port'])
            ->setEncryption($this->_smtp_config['encryption'])
            ->setUsername($mail)
            ->setPassword($password);

        $this->mailer = Swift_Mailer::newInstance($transport);
    }

    public static function config($host, $port, $encryption, $email, $password){
        require_once("../lib/swiftmailer/swiftmailer_required.php");

        $transport = Swift_SmtpTransport::newInstance()
            ->setHost($host)
            ->setPort($port)
            ->setEncryption($encryption)
            ->setUsername($email)
            ->setPassword($password);

        $this->mailer = Swift_Mailer::newInstance($transport);
    }

    public static function send($subject, $from, $to, $body, $part = "", $attachment = ""){
        $message = Swift_Message::newInstance()
        // Give the message a subject
        ->setSubject($subject)

        // Set the From address with an associative array
        ->setFrom($from)

        // Set the To addresses with an associative array
        ->setTo($to)

        // Give it a body
        ->setBody($body);

        // And optionally an alternative body
        if($part != ""){
            $message->addPart($part);
        }

        // Optionally add any attachments
        if($attachment != ""){
            $message->attach(Swift_Attachment::fromPath($attachment));
        }
        
        //send
        self::$mailer->send($message);
    }
}

?>