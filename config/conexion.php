<?php

define("HOSTNAME", "localhost");
define("DATABASE", "ces_reservas");
define("USERNAME", "root");
define("PASSWORD", "");
function conectar(){

    $dsn = "mysql:host=" . HOSTNAME . ";port=3308;dbname=" . DATABASE. ";charset=utf8mb4";

    try{
        $pdo = new PDO($dsn, USERNAME, PASSWORD);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }catch (PDOException $e){
        die("Error conectando a la base de datos: " . $e->getMessage());
    }

}