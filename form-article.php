<?php
    const ERROR_REQUIRED = "Veuillez renseigner ce champ";
    const ERROR_TITLE_TOO_SHORT = "Le titre est trop court";
    const ERROR_CONTENT_TOO_SHORT = "L'article est trop court";
    const ERROR_IMAGE_URL = "L'image doit etre une url valide";

    $pdo = require_once('./database/database.php');
    $statementCreateOne = $pdo->prepare(
        'INSERT INTO article (title, category, content, image) VALUES (:title, :category, :content, :image)'
    );
    $statementReadOne = $pdo->prepare('SELECT * FROM article WHERE id=:id');
    $statementUpdateOne = $pdo->prepare(
        'UPDATE article SET title=:title, category=:category, content=:content, image=:image WHERE id=:id'
    );

    $articles = [];
    $category = '';


    $errors = [
        'title' => '',
        'image' => '',
        'category' => '',
        'content' => ''
    ];

    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $idArticle = $_GET['id'] ?? '';

    if($idArticle) {
        $statementReadOne->bindValue(':id', $idArticle );
        $statementReadOne->execute();
        $article = $statementReadOne->fetch();

        $title = $article['title'];
        $image = $article['image'];
        $category = $article['category'];
        $content = $article['content'];
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_POST = filter_input_array(INPUT_POST, [
            'title' => FILTER_SANITIZE_SPECIAL_CHARS,
            'image' => FILTER_SANITIZE_URL,
            'category' => FILTER_SANITIZE_SPECIAL_CHARS,
            'content' => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                'flag' => FILTER_FLAG_NO_ENCODE_QUOTES
            ]
            ]);
        
        $title = $_POST['title'] ?? '' ;
        $image = $_POST['image'] ?? '' ;
        $category = $_POST['category'] ?? '' ;
        $content = $_POST['content'] ?? '' ;

        if(!$title) {
            $errors['title'] = ERROR_REQUIRED;
        } else if (mb_strlen($title) < 5) {
            $errors['title'] = ERROR_TITLE_TOO_SHORT;
        }

        if(!$image) {
            $errors['image'] = ERROR_REQUIRED;
        } else if (!filter_var($image, FILTER_VALIDATE_URL)) {
            $errors['image'] = ERROR_IMAGE_URL;
        }

        if(!$category) {
            $errors['category'] = ERROR_REQUIRED;
        }

        if(!$content) {
            $errors['content'] = ERROR_REQUIRED;
        } else if (mb_strlen($content) < 5) {
            $errors['content'] = ERROR_CONTENT_TOO_SHORT;
        }
        if(empty(array_filter($errors, fn ($error) => $error !== ''))) {
            // mon formulaire est valide

            if($idArticle) {
                $article['title'] = $title;
                $article['image'] = $image;
                $article['category'] = $category;
                $article['content'] = $content;

                $statementUpdateOne->bindValue(':title', $article['title']);
                $statementUpdateOne->bindValue(':category', $article['category']);
                $statementUpdateOne->bindValue(':content', $article['content']);
                $statementUpdateOne->bindValue(':image', $article['image']);
                $statementUpdateOne->bindValue(':id', $idArticle);
                $statementUpdateOne->execute();
            } else {
                $statementCreateOne->bindValue(':title', $title);
                $statementCreateOne->bindValue(':category', $category);
                $statementCreateOne->bindValue(':content', $content);
                $statementCreateOne->bindValue(':image', $image);
                $statementCreateOne->execute();
            }
            header('Location: /');
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="public/css/add-article.css">
    <title>Creer un article</title>
</head>
<body>
    <div class="container">
        <?php require_once "includes/header.php" ?>
        <div class="content">
            <div class="block p-20 form-container">
                <h1><?= $idArticle ? 'Editer': 'Ajouter' ?> un article</h1>
                <form action="/form-article.php<?= $idArticle ? "?id=$idArticle" : '' ?>" method="POST">
                    <div class="form-control">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" value="<?= $title ?? '' ?>">
                        <?php if($errors['title']) : ?>
                            <p class="text-error"><?= $errors['title'] ?></p>
                        <?php endif ; ?>
                    </div>

                    <div class="form-control">
                        <label for="image">Image</label>
                        <input type="text" name="image" id="image" value="<?= $image ?? '' ?>">
                        <?php if($errors['title']) : ?>
                            <p class="text-error"><?= $errors['title'] ?></p>
                        <?php endif ; ?>
                    </div>

                    <div class="form-control">
                        <label for="category">Categorie</label>
                        <select name="category" id="category" value="<?= $category ?? '' ?>">
                            <option <?= $category === 'technologie' ? 'selected': '' ?> value="technologie">Technologie</option>
                            <option <?= $category === 'nature' ? 'selected': '' ?> value="nature">Nature</option>
                            <option <?= $category === 'politique' ? 'selected': '' ?> value="politique">Politique</option>
                        </select>
                        <?php if($errors['title']) : ?>
                            <p class="text-error"><?= $errors['title'] ?></p>
                        <?php endif ; ?>
                    </div>

                    <div class="form-control">
                        <label for="content">Contenu</label>
                        <textarea name="content"><?= $content ?? '' ?></textarea>
                        <?php if($errors['title']) : ?>
                            <p class="text-error"><?= $errors['title'] ?></p>
                        <?php endif ; ?>
                    </div>

                    <div class="form-action">
                        <a href="/" class="btn btn-secondary">Annuler</a>
                        <button class="btn btn-primary" type="submit"><?= $idArticle ? 'Sauvegarder' : 'Poster' ?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once "includes/footer.php" ?>
    </div>
</body>
</html>