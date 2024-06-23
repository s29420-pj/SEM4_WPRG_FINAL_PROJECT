<?php

use App\Controllers\PostController;

require_once '../../vendor/autoload.php';

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$postID = $_GET['id'];

$postController = new PostController();
$postController->deletePost($postID);

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();