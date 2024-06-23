<?php

namespace App\Models;

use Exception;

class User
{
    public function createUser($username, $password): void
    {
        $db = new Database();
        $connection = $db->getConnection();

        // Check if a user with the given username already exists
        $stmt = $connection->prepare("SELECT * FROM wprg_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // A user with the given username already exists
            throw new Exception('A user with this username already exists');
        } else {
            // No user with the given username exists, so create a new user
            $defaultRole = 'USER';
            $stmt = $connection->prepare("INSERT INTO wprg_users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $defaultRole);
            $stmt->execute();
            $stmt->close();
        }
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
        $loggedInUserID = $user->getUserID();

        if (!$user->isLoggedIn() || $user->getUserRole($loggedInUserID) !== 'ADMIN') {
            throw new Exception('You must be logged in to update a user role, and you have to be an admin to update an user');
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("UPDATE wprg_users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $role, $userID);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteUser($userID)
    {
        $user = new User();
        if (!$user->isLoggedIn() || $user->getUserRole($user->getUserID()) !== 'ADMIN') {
            throw new Exception('You must be logged in to delete a user, and you have to be an admin to delete a user');
        }

        $connection = (new Database())->getConnection();
        $query = 'DELETE FROM wprg_users WHERE id = ?';
        $stmt = $connection->prepare($query);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $connection->close();
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

    public function getUsernameByID($user_id): bool|array|null
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT username FROM wprg_users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }
}