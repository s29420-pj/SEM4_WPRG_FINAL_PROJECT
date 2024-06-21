<?php

namespace App\Models;

class User
{
    public function createUser($username, $password, $role): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);
        $stmt->execute();
        $stmt->close();
    }

    public function getUser($userID)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function getUserRole($userID)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc()['role'];
    }

    public function updateUser($userID, $username, $password, $role): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $password, $role, $userID);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteUser($userID): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->close();
    }

    public function getUsers()
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function resetPassword($userID, $password): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $password, $userID);
        $stmt->execute();
        $stmt->close();
    }
}