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
                <a href="index.php">
                    <img src="./img/logo.png" alt="Logo" class="img-fluid" style="height: 120px; width: auto">
                </a>
            </div>
            <h1 class="text-center flex-grow-1 mb-0 logo">Szlakiem Przygód</h1>
            <nav>
                <ul class="nav">
                    <?php if ($loggedIn): ?>
                        <?php if ($userRole === 'ADMIN' || $userRole === 'AUTHOR'): ?>
                            <li class="nav-item me-2"><a href="createPost.php" class="btn btn-success">Utwórz Post</a></li>
                            <li class="nav-item me-2"><a href="mail.php" class="btn btn-info">Mail</a></li>
                        <?php endif; ?>
                        <?php if ($userRole === 'ADMIN'): ?>
                            <li class="nav-item me-2"><a href="admin.php" class="btn btn-dark">Panel Administratora</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item me-2"><a href="login.php" class="btn btn-light">Zaloguj</a></li>
                        <li class="nav-item me-2"><a href="register.php" class="btn btn-dark">Zarejestruj</a></li>
                    <?php endif; ?>
                    <?php if ($userRole !== 'AUTHOR' && $userRole !== 'ADMIN'): ?>
                        <li class="nav-item me-1"><a href="contact.php" class="btn btn-light">Kontakt</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="index.php" class="btn btn-light">Home</a></li>
                    <?php if ($loggedIn): ?>
                        <li class="nav-item me-1"><a href="profile.php" class="btn btn-light">Profil</a></li>
                        <li class="nav-item me-1"><a href="actions/logout.php" class="btn btn-danger">Wyloguj</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>
<div class="container">
    <main class="py-4">
        <hr class="my-4">
        <div class="col-md-12 mb-1">
            <div class="d-flex justify-content-end mb-2">
                <a class="btn btn-light btn-sm m-1" href="post.php?id=<?php echo $postController->previousPost($postId)?>">Poprzedni</a>
                <a class="btn btn-light btn-sm m-1" href="post.php?id=<?php echo $postController->nextPost($postId)?>">Następny</a>
            </div>
            <div class="card h-100">
                <img src="<?= $post['image'] ?>" class="card-img-top" alt="Post Image">
                <div class="card-body">
                    <h2 class="card-title"><?= $post['title'] ?></h2>
                    <p class="card-text"><?= $post['content'] ?></p>
                    <p class="card-text"><small class="text-muted">Autor: <?= $postController->getPostAuthor($post['id']) ?></small></p>
                    <p class="card-text"><small class="text-muted">Data: <?= $postController->getPostDate($post['id']) ?></small></p>
                </div>
            </div>
        </div>
    </main>
</div>
<div class="container">
    <div class="col-md-12 mb-5">
        <h2>Komentarze</h2>
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
                    <p class="card-text">
                        <small class="text-muted">
                            Autor:
                            <?php
                            $authorID = $comment['wprg_users_id'];
                            if ($authorID) {
                                echo $userController->getUsernameByID($authorID)['username'];
                            } else {
                                echo 'Gość';
                            }
                            ?>
                        </small>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>

        <form action="actions/createComment.php" method="post">
            <div class="form-group">
                <label for="content">Napisz Komentarz</label>
                <textarea class="form-control" id="content" name="content" required></textarea>
                <?php if (isset($_GET['error'])): ?>
                    <p class="text-danger">Komentarz nie może być pusty.</p>
                <?php endif; ?>
            </div>
            <input type="hidden" name="post_id" value="<?= $postId ?>">
            <button type="submit" class="btn btn-primary mt-3">Dodaj Komentarz</button>
        </form>
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