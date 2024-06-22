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
        $this->commentModel->createComment($content, $postID, $userID, date('Y-m-d H:i:s'));
        $this->logger->createLog('Comment created', date('Y-m-d H:i:s'));
    }

    public function deleteComment($commentID)
    {
        $this->commentModel->deleteComment($commentID);
        $this->logger->createLog('Comment deleted', date('Y-m-d H:i:s'));
    }

    public function getCommentsByPostID($postID)
    {
        return $this->commentModel->getCommentsByPostID($postID);
    }
}