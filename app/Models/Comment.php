<?php

namespace App\Models;

class Comment
{
    public function createComment($content, $postID, $userID, $date): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("INSERT INTO comments (content, post_id, user_id, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $content, $postID, $userID, $date);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteComment($commentID): void
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentID);
        $stmt->execute();
        $stmt->close();
    }

    public function getCommentsByPostID($postID): array
    {
        $db = new Database();
        $connection = $db->getConnection();
        $stmt = $connection->prepare("SELECT * FROM comments WHERE post_id = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

}