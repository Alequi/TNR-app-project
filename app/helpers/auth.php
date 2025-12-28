<?php
session_start();

function login(){
    if (!isset($_SESSION['email'])) {
        header("Location: ../../public/login.php");
        exit();
    };
}

function admin(){

    login();

    if(!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin'){
         header("Location: ../../public/login.php");
        exit();
    }
}
?>