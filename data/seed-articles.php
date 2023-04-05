<?php

    $articles = json_decode(file_get_contents('articles.json'), true);

    // PDO : PHP Data Object => il va nous permettre de communiquer avec une base de donnees
    $url = 'mysql:host=localhost:8889;dbname=blog';
    $user = 'root';
    $pwd = 'root';

    $pdo = new PDO($url, $user, $pwd);

    $requetSql = 'INSERT INTO article (title, category, content, image) VALUES (:title, :category, :content, :image)';
    $statement = $pdo->prepare($requetSql);

    foreach ($articles as $article) {
        $statement->bindValue(':title', $article['title']);
        $statement->bindValue(':category', $article['category']);
        $statement->bindValue(':content', $article['content']);
        $statement->bindValue(':image', $article['image']);
        $statement->execute();
    }