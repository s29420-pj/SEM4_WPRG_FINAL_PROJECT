<?php

namespace App\Controllers;

use App\Models\Log;
use App\Models\Post;
use Exception;

class PostController
{
    protected Post $postModel;
    protected Log $logger;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->logger = new Log();
    }

    public function createPost($title, $content, $image, $userID): void
    {
        try {
            $this->postModel->createPost($title, $content, $image, $userID, date('Y-m-d H:i:s'));
            $this->logger->createLog('Post created', date('Y-m-d H:i:s'));
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            header("Location: error.php");
        }

    }

    public function editPost($postID, $title, $content, $image): void
    {
        try {
            $this->postModel->editPost($postID, $title, $content, $image);
            $this->logger->createLog('Post edited', date('Y-m-d H:i:s'));
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            header("Location: error.php");
        }
    }

    public function deletePost($postID): void
    {
        try {
            $this->postModel->deletePost($postID);
            $this->logger->createLog('Post deleted', date('Y-m-d H:i:s'));
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            header("Location: error.php");
        }
    }

    public function getPostByID($postID): bool|array|null
    {
        return $this->postModel->getPostByID($postID);
    }

    public function getPosts(): array
    {
        return $this->postModel->getPosts();
    }

    public function sortPostsByDate(): array
    {
        return $this->postModel->sortPostsByDate();
    }

    public function getPostAuthor($postID)
    {
        return $this->postModel->getPostAuthor($postID);
    }

    public function getPostDate($postID)
    {
        return $this->postModel->getPostDate($postID);
    }

    public function nextPost($postID): ?int
    {
        return $this->postModel->nextPost($postID);
    }

    public function previousPost($postID): ?int
    {
        return $this->postModel->previousPost($postID);
    }
}