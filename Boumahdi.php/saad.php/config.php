<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$db   = 'ecom1_project';
$user = 'root';
$pass = '';
$port = '3306';
$charset = 'utf8mb4';

// Connexion à la base de données avec MySQLi
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// Vérification de la connexion
if (!$conn) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}
?>
