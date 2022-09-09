<?php

define('DB_SERVER', '192.168.1.67');
define('DB_USERNAME', 'tauro');
define('DB_PASSWORD', 'taurodbraspy');
define('DB_NAME', 'progettotest');
 
try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Connessione fallita. " . $e->getMessage());
}
