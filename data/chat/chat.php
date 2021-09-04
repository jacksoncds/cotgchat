<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;

    protected $chats;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->chats = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function send($clients, $sender, $message)
    {
        $msg = [
            "playerColor"   => "#03a9f4",
            "textColor"     => "#ff22d8",
            "time"          => date("U"),
            "player"        => $sender->chatUser->name,
            "message"       => $message,
            "rank"          => $sender->chatUser->rank
        ];

        foreach ($clients as $client) {
            $client->send("{\"chatMessage\":" . json_encode($msg) . "}");
        }
    }

    public function sendCommand($clients, $sender, $message)
    {
        foreach ($clients as $client) {
            $client->send("{\"status\": {\"status\":\"$message\"}}");
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {

        //Commands
        if (substr($msg, 0, 2) == '!@') {
            if (substr($msg, 0, 6) == '!@auth') {
                require_once('chatauth.php');

                $authenticator = new ChatAuth();
                if ($authenticator->auth(substr($msg, 6))) {
                    require_once('chatuser.php');

                    echo "Authed::" . $from->resourceId;

                    //Set up user
                    $chatUser = new ChatUser();
                    $chatUser->setConnection($from);
                    $chatUser->initUser($authenticator->name, $authenticator->chatId, $authenticator->cKey);
                    $from->chatUser = $chatUser;

                    //Chat array exists
                    if (array_key_exists('chat_id_' . $authenticator->chatId, $this->chats)) {
                        array_push($this->chats['chat_id_' . $authenticator->chatId], $from);
                    } else {
                        $this->chats['chat_id_' . $authenticator->chatId] = [];
                        array_push($this->chats['chat_id_' . $authenticator->chatId], $from);
                    }

                    $chatUser->getConnection()->send("{\"status\": {\"status\":\"Authorized!\"}}");
                } else {
                    echo 'False';
                    $from->send(
                        "{\"status\": 
                                {\"status\":\"Something went wrong, and you could not be authorized!\"}}"
                    );
                }
            }
        } else {

            //Check if user is auth
            if (isset($from->chatUser)) {
                $numRecv = sizeof($this->chats['chat_id_' . $from->chatUser->chatId]) - 1;
                echo sprintf(
                    'Connection %d sending message "%s" to %d other connection%s' . "\n",
                    $from->resourceId,
                    $msg,
                    $numRecv,
                    $numRecv == 1 ? '' : 's'
                );

                $this->send($this->chats['chat_id_' . $from->chatUser->chatId], $from, $msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        //If conn is authorized
        if (isset($conn->chatUser)) {
            for ($i = 0; $i < sizeof($this->chats['chat_id_' . $conn->chatUser->chatId]); $i++) {
                if ($this->chats['chat_id_' . $conn->chatUser->chatId][$i]->chatUser->getConnection()->resourceId == $conn->resourceId) {

                    $disconnectMessage = "User " . $conn->chatUser->name . " has disconnected.";
                    $this->sendCommand($this->chats['chat_id_' . $conn->chatUser->chatId], $conn, $disconnectMessage);

                    echo "User " . $conn->chatUser->name . " has disconnected\n";

                    array_splice($this->chats['chat_id_' . $conn->chatUser->chatId], $i, 1);
                }
            }

            //Destroy connection from memory
            unset($conn);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
