<?php

namespace App\Models;

use Exception;

class User
{
    public function createUser($username, $password): void
    {
        $defaultRole = 'USER';

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $defaultRole);
        $stmt->execute();
        $stmt->close();
    }

    public function getUser($userID): bool|array|null
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

    public function updateUserRole($userID, $role): void
    {
        $user = new User();
        if (!$user->isLoggedIn() && $user->getUserRole($userID) === 'ADMIN') {
            throw new Exception('You must be logged in to update a user role, and you have to be an admin to update an user');
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $role, $userID);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteUser($userID): void
    {
        $user = new User();
        if (!$user->isLoggedIn() && $user->getUserRole($userID) === 'ADMIN') {
            throw new Exception('You must be logged in to delete a user, and you have to be an admin to delete an user');
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->close();
    }

    public function getUsers(): array
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
        $user = new User();
        if (!$user->isLoggedIn()) {
            throw new Exception('You must be logged in to reset your password');
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $password, $userID);
        $stmt->execute();
        $stmt->close();
    }

    public function login($username, $password): bool|array|null
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function isLoggedIn(): bool
    {
        session_start();
        return isset($_SESSION['user_id']);
    }

    public function logout(): void
    {
        $user = new User();
        if (!$user->isLoggedIn()) {
            header('Location: /index.php');
            exit();
        }

        session_start();
        session_destroy();
    }
}