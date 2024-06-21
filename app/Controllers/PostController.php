<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Log;

class PostController
{
    protected $postModel;
    protected $logger;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->logger = new Log();
    }

    public function createPost($title, $content, $image, $userID): void
    {
        $this->postModel->createPost($title, $content, $image, $userID, time());
        $this->logger->createLog('Post created', time());
    }

    public function editPost($postID, $title, $content, $image): void
    {
        $this->postModel->editPost($postID, $title, $content, $image);
        $this->logger->createLog('Post edited', time());
    }

    public function deletePost($postID): void
    {
        $this->postModel->deletePost($postID);
        $this->logger->createLog('Post deleted', time());
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

}