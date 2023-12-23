<?php
session_start(); // Démarrer la session

$_SESSION = array(); // Vider le tableau de session pour supprimer toutes les données de session
session_destroy(); // Détruire la session actuelle

header("location: login.php"); // Rediriger vers la page de connexion
?>
