<?php 
    /**
     * @var ArticleDAO
     */
    $articleDAO = require_once './database/models/articleDAO.php';

    $articles = [];

    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $idArticle = $_GET['id'] ?? "";

    if($idArticle) {
        $articleDAO->DeleteOne($idArticle);
    }

    header('Location: /');