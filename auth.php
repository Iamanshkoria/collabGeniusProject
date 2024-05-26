<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


function isLoggedIn() {
    return isset($_SESSION['username']);
}


function getUserID() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}


function redirectToLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function redirectToProfileCreation() {
    if (isLoggedIn() && !isset($_SESSION['profile_created']) && basename($_SERVER['PHP_SELF']) != 'create_profile.php') {
        header("Location: create_profile.php");
        exit();
    }
}


function redirectToHome() {
    if (isLoggedIn() && isset($_SESSION['profile_created'])) {
        header("Location: home.php");
        exit();
    }
}


$allowed_pages = array('login.php', 'register.php', 'about.php', 'contact.php');

?>
