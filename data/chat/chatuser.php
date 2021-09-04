<?php
class ChatUser
{
    public $isAuthorized = false;
    public $isOnlineStatus = false;
    private $connection;

    public $userId = -1;
    public $name = "";
    public $cKey = "";
    public $pColor = "";
    public $mColor = "";
    public $rank = "";
    public $allianceId = -1;
    public $memberId = -1;
    public $chatId = -1;

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function initUser($userName, $cId, $cKey)
    {
        //TODO: Get user info

        require_once(__DIR__ . '/../api/dbconnect.php');

        $query = "  SELECT 
                        users.id, 
                        users.name, 
                        users.cKey, 
                        users.pColor, 
                        users.mColor, 
                        users.allianceId, 
                        users.memberId, 
                        users.rank,
                        chats.id AS chatId 
                    FROM 
                        users 
                    LEFT JOIN 
                        user_chat_ref 
                    ON 
                        users.id = user_chat_ref.userId 
                    LEFT JOIN 
                        chats ON user_chat_ref.chatId = chats.id 
                    WHERE 
                        users.name = ?
                    AND 
                        chats.id = ?
                    AND
                        users.cKey = ?";

        $SQLConnection = Database::connect();

        $stmt = $SQLConnection->prepare($query);

        $stmt->bind_param('sis', $userName, $cId, $cKey);

        $stmt->execute();

        $stmtResult = $stmt->get_result();

        while ($result = $stmtResult->fetch_assoc()) {
            $this->userId = $result['id'];
            $this->name = $result['name'];
            $this->cKey = $result['cKey'];
            $this->pColor = $result['pColor'];
            $this->mColor = $result['mColor'];
            $this->rank = $result['rank'];
            $this->allianceId = $result['allianceId'];
            $this->memberId = $result['memberId'];
            $this->chatId = $result['chatId'];
        }
    }
}
