<?php

namespace App\Models;

class Post
{
    public function createPost($title, $content, $image, $userID, $date): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("INSERT INTO posts (title, content, image, user_id, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $content, $image, $userID, $date);
        $stmt->execute();
        $stmt->close();
    }

    public function editPost($postID, $title, $content, $image): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $content, $image, $postID);
        $stmt->execute();
        $stmt->close();
    }

    public function deletePost($postID): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $stmt->close();
    }

    public function getPostByID($postID): bool|array|null
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM posts WHERE id = ?");
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
        $stmt = $connection->prepare("SELECT * FROM posts");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function sortPostsByDate(): array
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM posts ORDER BY date DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostAuthor($postID)
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT username FROM users JOIN posts ON users.id = posts.user_id WHERE posts.id = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc()['username'];
    }
}