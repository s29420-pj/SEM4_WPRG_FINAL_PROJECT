<?php

namespace App\Models;

use Exception;

class Comment
{
    public function createComment($content, $postID, $userID, $date): void
    {
        $user = new User();

        if (empty($content)) {
            throw new Exception('Comment content cannot be empty');
        }
        if (!$user->isLoggedIn()) {
            $userID = null;
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("INSERT INTO wprg_comments (content, wprg_posts_id, wprg_users_id, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siis", $content, $postID, $userID, $date);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteComment($commentID): void
    {
        $user = new User();
        if (!$user->isLoggedIn() || $user->getUserRole($user->getUserID()) !== 'ADMIN') {
            throw new Exception('You must be logged in to delete a comment and you have to be an admin to delete a comment');
        }

        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("DELETE FROM wprg_comments WHERE id = ?");
        $stmt->bind_param("i", $commentID);
        $stmt->execute();
        $stmt->close();
    }

    public function getCommentsByPostID($postID): array
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM wprg_comments WHERE wprg_posts_id = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getComments(): array
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM wprg_comments");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}