<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\CommentController;

$postController = new PostController();
$userController = new UserController();
$commentController = new CommentController();

// Get the ID from the URL
$postId = $_GET['id'];

// Fetch the post from the database
$post = $postController->getPostByID($postId);
$loggedIn = $userController->isLoggedIn();
$userRole = $loggedIn ? $userController->getUserRole($userController->getUserID()) : null;

// If the post is not found, redirect to the index page
if (!$post) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $post['title'] ?></title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+NG+Modern:wght@100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<style>
    .logo {
        font-family: 'Playwrite NG Modern', sans-serif;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }
</style>
<body class="d-flex flex-column vh-100">
<header class="bg-light py-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="./img/logo.png" alt="Logo" class="img-fluid" style="height: 120px; width: auto">
            </div>
            <h1 class="text-center flex-grow-1 mb-0 logo">Szlakiem Przygód</h1>
            <nav>
                <ul class="nav">
                    <?php if ($loggedIn): ?>
                        <li class="nav-item me-3"><a href="logout.php" class="btn btn-danger">LogOut</a></li>
                        <?php if ($userRole === 'ADMIN'): ?>
                            <li class="nav-item me-3"><a href="admin.php" class="btn btn-dark">Admin Panel</a></li>
                        <?php endif; ?>
                        <?php if ($userRole === 'AUTHOR' || $userRole === 'ADMIN'): ?>
                            <li class="nav-item me-3"><a href="createPost.php" class="btn btn-dark">Create Post</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item me-3"><a href="login.php" class="btn btn-light">Login</a></li>
                        <li class="nav-item me-3"><a href="register.php" class="btn btn-dark">Register</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="contact.php" class="btn btn-light">Contact</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<div class="container">
    <hr class="my-4">
    <main class="py-4">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card h-100">
                    <img src="<?= $post['image'] ?>" class="card-img-top" alt="Post Image">
                    <div class="card-body">
                        <h2 class="card-title"><?= $post['title'] ?></h2>
                        <p class="card-text"><?= $post['content'] ?></p>
                        <p class="card-text"><small class="text-muted">Author: <?= $postController->getPostAuthor($post['id']) ?></small></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Comments</h2>
            <?php foreach ($commentController->getCommentsByPostID($postId) as $comment): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title
                        <?php if ($comment['wprg_users_id'] === $userController->getUserID()): ?>
                            text-primary
                        <?php endif; ?>
                        ">
                            <?= $comment['content'] ?>
                        </h5>
                        <p class="card-text"><small class="text-muted">Author: <?= $userController->getUsernameByID($comment['wprg_users_id'])['username'] ?></small></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($loggedIn): ?>
                <form action="createComment.php" method="post">
                    <div class="form-group
                    <?php if (isset($_GET['error'])): ?>
                        has-error
                    <?php endif; ?>
                    ">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" required></textarea>
                        <?php if (isset($_GET['error'])): ?>
                            <p class="text-danger">Comment content cannot be empty</p>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="post_id" value="<?= $postId ?>">
                    <button type="submit" class="btn btn-primary">Add Comment</button>
                </form>
            <?php else: ?>
                <p>You must be logged in to add a comment</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>Indeks: s29420</p>
    </div>
</footer>
<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>