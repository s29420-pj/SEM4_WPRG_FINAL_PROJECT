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
        $stmt = $connection->prepare("INSERT INTO wprg_users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $defaultRole);
        $stmt->execute();
        $stmt->close();
    }

    public function getUser($userID): bool|array|null
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM wprg_users WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function getUserID()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['id'] ?? null;
    }

    public function getUserRole($userID)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT role FROM wprg_users WHERE id = ?");
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
        $stmt = $connection->prepare("UPDATE wprg_users SET role = ? WHERE id = ?");
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
        $stmt = $connection->prepare("DELETE FROM wprg_users WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->close();
    }

    public function getUsers(): array
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM wprg_users");
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
        $stmt = $connection->prepare("UPDATE wprg_users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $password, $userID);
        $stmt->execute();
        $stmt->close();
    }

    public function login($username, $password): bool|array|null
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM wprg_users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $user = $result->fetch_assoc();

        if ($user) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['id'] = $user['id'];
            return true;
        } else {
            return false;
        }
    }

    public function isLoggedIn(): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['id']);
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }
}