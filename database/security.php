<?php



    function isLoggedIn() {
        /**
        * @var authDAO
        */
        $authDAO = require_once './database/models/authDAO.php';
        $sessionId = $_COOKIE["session"] ?? '';

        if($sessionId) {
            $session = $authDAO->getSessionById($sessionId);

            if($session) {
                $user = $authDAO->getUserById($session['userid']);
            }
        }

        return $user ?? false;
    }


?>