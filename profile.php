<?php 

    require __DIR__.'/database/database.php';
    $authDAO = require './database/models/AuthDAO.php';
    $currentUser = $authDAO->isLoggedIn();

    $articleDAO = require __DIR__.'/database/models/articleDAO.php';
    $articles = [];

    if(!$currentUser) {
        header('Location: /auth-login.php');
    }

    $articles = $articleDAO->getArticlesForCurrentUser($currentUser['id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="public/css/profile.css">
    <title>Article</title>
</head>
<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <h1>Mon Espace</h1>
            <h3>Mes informations</h3>
            <ul>
                <li>
                    <strong>Prenom: </strong>
                    <p><?= $currentUser['firstname'] ?></p>
                </li>
                <li>
                    <strong>Nom: </strong>
                    <p><?= $currentUser['lastname'] ?></p>
                </li>
                <li>
                    <strong>email: </strong>
                    <p><?= $currentUser['email'] ?></p>
                </li>
            </ul>
            <h3>Mes Articles</h3>
            <div class="articles-list">
                <ul>
                    <?php foreach ($articles as $article) : ?>
                        <li>
                            <span><?= $article['title'] ?></span>
                            <div class="article-action">
                                <a href="/form-article.php?id=<?= $article['id'] ?>" class="btn btn-primary">Editer l'article</a>
                                <a href="/delete-article.php?id=<?= $article['id'] ?>" class="btn btn-secondary">Supprimer</a>
                            </div>
                        </li>
                    <?php  endforeach;?>
                </ul>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>
</body>
</html>