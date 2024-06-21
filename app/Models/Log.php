<?php

namespace App\Models;

class Log
{
    public function createLog($userID, $action, $date): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("INSERT INTO logs (user_id, action, date) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userID, $action, $date);
        $stmt->execute();
        $stmt->close();
    }

    public function getLogs(): array
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM logs");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}