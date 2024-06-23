<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Log;
use Exception;

class CommentController
{
    protected Comment $commentModel;
    protected Log $logger;

    public function __construct()
    {
        try {
            $this->commentModel = new Comment();
            $this->logger = new Log();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function createComment($content, $postID, $userID): void
    {
        try {
            $this->commentModel->createComment($content, $postID, $userID, date('Y-m-d H:i:s'));
            $this->logger->createLog('Comment created', date('Y-m-d H:i:s'));
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            header("Location: error.php");
        }
    }

    public function deleteComment($commentID): void
    {
        try {
            $this->commentModel->deleteComment($commentID);
            $this->logger->createLog('Comment deleted', date('Y-m-d H:i:s'));
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            header("Location: error.php");
        }
    }

    public function getCommentsByPostID($postID): array
    {
        return $this->commentModel->getCommentsByPostID($postID);
    }

    public function getComments(): array
    {
        return $this->commentModel->getComments();
    }
}