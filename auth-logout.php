<?php

    require __DIR__.'/database/database.php';
    $authDAO = require './database/models/authDAO.php';

    $sessionId = $_COOKIE["session"];
    if($sessionId) {
        $authDAO->logout($sessionId);
        setcookie('session', '', time() - 1);
        header('Location: /auth-login.php');
    }

