<?php
$host = "mysql-loute.alwaysdata.net"; 
$user = "loute";
$password = "rootlaboG2";
$dbname = "loute_labo";
$port = 3306;

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ]
    );
    echo "✅ Connexion PDO réussie";
} catch (PDOException $e) {
    die("❌ Erreur PDO : " . $e->getMessage());
}