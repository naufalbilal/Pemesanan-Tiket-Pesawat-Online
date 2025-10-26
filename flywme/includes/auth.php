<?php
if (!isset($_SESSION)) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function checkAuth() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}

function checkAdmin() {
    if (!isAdmin()) {
        header('Location: ../index.php');
        exit();
    }
}
?>