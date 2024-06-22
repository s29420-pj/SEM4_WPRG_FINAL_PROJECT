<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;
use App\Controllers\PostController;

$user = new UserController();
$postController = new PostController();

$posts = $postController->getPosts();
$loggedIn = $user->isLoggedIn();
$userRole = $loggedIn ? $user->getUserRole($user->getUserID()) : null;
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szlakiem Przygód</title>
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
                <a href="index.php">
                    <img src="./img/logo.png" alt="Logo" class="img-fluid" style="height: 120px; width: auto">
                </a>
            </div>
            <h1 class="text-center flex-grow-1 mb-0 logo">Szlakiem Przygód</h1>
            <nav>
                <ul class="nav">
                    <?php if ($loggedIn): ?>
                        <li class="nav-item me-3"><a href="actions/logout.php" class="btn btn-danger">LogOut</a></li>
                        <?php if ($userRole === 'ADMIN' || $userRole === 'AUTHOR'): ?>
                            <li class="nav-item me-3"><a href="createPost.php" class="btn btn-primary">Create Post</a></li>
                        <?php endif; ?>
                        <?php if ($userRole === 'ADMIN'): ?>
                            <li class="nav-item me-3"><a href="admin.php" class="btn btn-dark">Admin Panel</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item me-3"><a href="login.php" class="btn btn-light">Login</a></li>
                        <li class="nav-item me-3"><a href="register.php" class="btn btn-dark">Register</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="contact.php" class="btn btn-light">Contact</a></li>
                    <li class="nav-item"><a href="index.php" class="btn btn-light">Home</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<div class="container">
    <hr class="my-4">
    <main class="py-4">
        <div class="row">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-4 mb-4">
                    <a href="post.php?id=<?= $post['id'] ?>" style="text-decoration: none; color: inherit;">
                        <div class="card h-100">
                            <img src="<?= $post['image'] ?>" class="card-img-top" alt="Post Image">
                            <div class="card-body">
                                <h2 class="card-title"><?= $post['title'] ?></h2>
                                <?php
                                $words = str_word_count($post['content'], 1);
                                if (count($words) > 30) {
                                    $words = array_slice($words, 0, 30);
                                    $post['content'] = implode(' ', $words) . '...';
                                }
                                ?>
                                <p class="card-text"><?= $post['content'] ?></p>
                                <p class="card-text"><small class="text-muted">Author: <?= $postController->getPostAuthor($post['id']) ?></small></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>
<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>Indeks: s29420</p>
    </div>
</footer>
<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>