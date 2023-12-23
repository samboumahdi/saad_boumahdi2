<?php
// Inclusion du fichier de configuration
include('config.php');
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obtenir l'ID de l'utilisateur
$user_id = $_SESSION['user_id'];

// Récupérer les commandes pour l'utilisateur actuel
$sql = "SELECT * FROM user_order WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);

// Vérification de la préparation de la requête
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    
    // Récupérer le résultat de la requête
    $result = mysqli_stmt_get_result($stmt);

    // Vérifier si des commandes ont été trouvées
    if (mysqli_num_rows($result) > 0) {
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Afficher les commandes
        echo '<h2>Liste des commandes en cours :</h2>';
        echo '<ul>';
        foreach ($orders as $order) {
            echo '<li>';
            echo 'ID de la commande : ' . $order['id'] . '<br>';
            echo 'Ref de la commande : ' . $order['ref'] . '<br>';
            echo 'Date de la commande : ' . $order['date'] . '<br>';
            echo 'Total prix de la commande : ' . $order['total'] . '<br>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo 'Aucune commande en cours.';
    }

    // Fermeture du statement
    mysqli_stmt_close($stmt);
} else {
    // En cas d'erreur dans la préparation de la requête
    die("Erreur lors de la préparation de la requête : " . mysqli_error($conn));
}

// Fermeture de la connexion MySQLi
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes en cours</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fff;
            margin: 10px 0;
            padding: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            color: #333;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <a href="Accueil_client.php">Retour à la page d'accueil client</a>
</body>
</html>
