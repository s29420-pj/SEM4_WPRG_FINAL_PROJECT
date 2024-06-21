<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Log;

class CommentController
{
    protected $commentModel;
    protected $logger;

    public function __construct()
    {
        $this->commentModel = new Comment();
        $this->logger = new Log();
    }

    public function createComment($content, $postID, $userID)
    {
        $this->commentModel->createComment($content, $postID, $userID, time());
        $this->logger->createLog('Comment created', time());
    }

    public function deleteComment($commentID)
    {
        $this->commentModel->deleteComment($commentID);
        $this->logger->createLog('Comment deleted', time());
    }

    public function getCommentsByPostID($postID)
    {
        return $this->commentModel->getCommentsByPostID($postID);
    }
}