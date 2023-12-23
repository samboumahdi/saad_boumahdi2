<?php
// Démarre la session PHP
session_start();

// Inclut le fichier de configuration qui contient la connexion à la base de données
include('config.php');

// Dossier où sont stockées les images des produits
$dossierImageProduit = "Image_produit/";

// Redirige vers la page de connexion si l'utilisateur n'est pas connecté
if(!$_SESSION['user_id']){
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Afficher tous les produits</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .produit {
            max-width: 300px;
            background-color: #fff;
            padding: 20px;
            margin: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        p {
            color: #555;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            opacity: 0.8;
        }

        .delete-button {
            background-color: red;
            color: white;
        }

        .edit-button {
            background-color: green;
            color: white;
        }

        .home-button {
            background-color: #007bff;
            color: white;
        }
        /* Ajoutez cette règle pour augmenter la taille du bouton */
        .home-button {
            padding: 10px 20px; /* Ajustez les valeurs selon vos besoins */
            font-size: 16px;   /* Ajustez la taille de la police selon vos besoins */
        }
    </style>
</head>
<body>
<?php


// Récupère tous les produits de la base de données
$recupProduit = mysqli_query($conn, 'SELECT * FROM product');



// Afficher les détails de chaque produit
while ($produit = mysqli_fetch_assoc($recupProduit)) {
    echo '<div class="produit">
            <h1>' . $produit['name'] . '</h1>
            <p>' . $produit['description'] . '</p>
            <p>' . $produit['quantity'] . '</p>
            <p>' . $produit['price'] . '</p>
            <img src="' . $dossierImageProduit . $produit['img_url'] . '" alt="Image du produit" style="max-width: 90%; height: auto;">
            <br>
            <a href="supprimer-produit.php?id=' . $produit['id'] . '">
                <button class="delete-button">Supprimer produit</button>
            </a>
            <a href="Modifier_produit.php?id=' . $produit['id'] . '">
                <button class="edit-button">Modifier produit</button>
            </a>
          </div>';
}

// Bouton pour retourner à la page d'accueil de l'administrateur
echo '<a href="Accueil_admin.php">
        <button class="home-button">Retour à l\'accueil</button>
      </a>';

// Fermer la connexion à la base de données à la fin du script
mysqli_close($conn);
?>
