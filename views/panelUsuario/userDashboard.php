<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../app/helpers/auth.php';
login();
$con = conectar();
?>