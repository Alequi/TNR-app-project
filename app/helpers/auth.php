<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

function login(){
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../public/login.php");
        exit();
    };
}

function admin(){

    login();

    if ($_SESSION['rol'] !== 'admin') {
        header("Location: ../../public/login.php");
        exit();
    }
}

function isLoggedIn(){
    return isset($_SESSION['user_id']);
}