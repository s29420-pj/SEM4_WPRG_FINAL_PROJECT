<?php

namespace App\Models;

class Log
{
    public function createLog($message, $timestamp): void
    {
        $user = new User();
        if($user->isLoggedIn()) {
            $userID = $user->getUserID();
        } else {
            $userID = null;
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("INSERT INTO wprg_logs (action, user_id, timestamp) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $message, $userID, $timestamp);
        $stmt->execute();
        $stmt->close();
    }

    public function getLogs(): array
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM wprg_logs");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}