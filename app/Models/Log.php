<?php

namespace App\Models;

class Log
{
    public function createLog($message, $timestamp): void
    {
        $user = new User();
        $userID = $user->getUserID();

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("INSERT INTO logs (action, user_id, timestamp) VALUES (?, ?, ?)");
        $stmt->bind_param("si", $message, $userID, $timestamp);
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