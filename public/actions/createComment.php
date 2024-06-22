<?php

use App\Controllers\CommentController;
use App\Controllers\UserController;

require_once '../../vendor/autoload.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $postId = $_POST['post_id'];

    // Assuming you have a UserController and a method to get the logged in user's ID
    $userController = new UserController();
    $userId = $userController->getUserID();

    if (empty($content)) {
        header("Location: post.php?id=$postId&error=empty content");
        exit();
    }

    $commentController = new CommentController();
    $commentController->createComment($content, $postId, $userId);

    header("Location: ../post.php?id=$postId");
} else {
    header("Location: ../index.php");
}