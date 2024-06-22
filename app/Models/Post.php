<?php

namespace App\Models;

use Exception;
use App\Models\Database;

class Post
{
    public function createPost($title, $content, $image, $userID, $date): void
    {
        $user = new User();
        if (!$user->isLoggedIn() || ($user->getUserRole($userID) !== 'ADMIN' || $user->getUserRole($userID) !== 'AUTHOR')) {
            throw new Exception('You must be logged in to create a post and you have to be an admin or an author to create a post');
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("INSERT INTO wprg_posts (title, content, image, date, wprg_users_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $content, $image, $date, $userID);
        $stmt->execute();
        $stmt->close();
    }

    public function editPost($postID, $title, $content, $image): void
    {
        $user = new User();
        if (!$user->isLoggedIn() || ($user->getUserRole($user->getUserID()) !== 'ADMIN' || $user->getUserRole($user->getUserID()) !== 'AUTHOR')) {
            throw new Exception('You must be logged in to edit a post and you have to be an admin or an author to edit a post');
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("UPDATE wprg_posts SET title = ?, content = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $content, $image, $postID);
        $stmt->execute();
        $stmt->close();
    }

    public function deletePost($postID): void
    {
        $user = new User();
        if (!$user->isLoggedIn() || $user->getUserRole($user->getUserID()) !== 'ADMIN') {
            throw new Exception('You must be logged in to delete a post and you have to be an admin to delete a post');
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("DELETE FROM wprg_posts WHERE id = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $stmt->close();
    }

    public function getPostByID($postID): bool|array|null
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM wprg_posts WHERE id = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function getPosts(): array
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM wprg_posts");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function sortPostsByDate(): array
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM wprg_posts ORDER BY date DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostAuthor($postID)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT username FROM wprg_users JOIN wprg_posts ON wprg_users.id = wprg_posts.wprg_users_id WHERE wprg_posts.id = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc()['username'];
    }
}