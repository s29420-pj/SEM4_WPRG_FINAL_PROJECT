<?php

use App\Controllers\CommentController;

require_once '../../vendor/autoload.php';

// Check if the comment ID is provided
if (!isset($_GET['id'])) {
    header("Location: ../admin.php");
    exit();
}

$commentId = $_GET['id'];

$commentController = new CommentController();
$commentController->deleteComment($commentId);

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();