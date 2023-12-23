<?php
session_start();
include('config.php');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$dossierImageProduit = "Image_produit/";

// Vérifie si le formulaire a été soumis
if (isset($_POST['envoi'])) {
    // Vérifie si tous les champs nécessaires sont renseignés
    if (!empty($_POST['name']) && !empty($_POST['quantity']) && !empty($_POST['price']) && !empty($_POST['description'])) {
        $name = htmlspecialchars($_POST['name']);
        $quantity = intval($_POST['quantity']);
        $price = floatval($_POST['price']);
        $description = nl2br(htmlspecialchars($_POST["description"]));
        $filename = '';

        // Gestion du fichier image
        if (!empty($_FILES['img_url']['name'])) {
            $img_url = $_FILES['img_url']['name'];
            $filename = uniqid() . $img_url;

            // Vérifie si le fichier est une image
            $imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $allowableTypes = array("jpg", "jpeg", "png", "gif");

            if (in_array($imageFileType, $allowableTypes)) {
                move_uploaded_file($_FILES['img_url']['tmp_name'], $dossierImageProduit . $filename);

                // Prépare et exécute la requête d'insertion du produit dans la base de données
$insererProduit = mysqli_prepare($conn, 'INSERT INTO product(name, quantity, price, img_url, description) VALUES(?, ?, ?, ?, ?)');
mysqli_stmt_bind_param($insererProduit, 'sidss', $name, $quantity, $price, $filename, $description);

                if (mysqli_stmt_execute($insererProduit)) {
                    echo "Le produit a bien été ajouté";
                } else {
                    echo "Erreur lors de l'ajout du produit : " . mysqli_error($conn);
                }

                mysqli_stmt_close($insererProduit);
            } else {
                echo "Le fichier n'est pas une image valide";
            }
        } else {
            echo "Veuillez sélectionner une image";
        }
    } else {
        echo "Veuillez compléter tous les champs.";
    }
}

// Ferme la connexion à la base de données à la fin du script
mysqli_close($conn);
?>






<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un produit</title>
    <meta charset="utf-8">
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

        form {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input, textarea {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="name">Nom du produit :</label>
        <input type="text" name="name" required>
        <br>
        <label for="quantity">Quantité :</label>
        <input type="number" name="quantity" required>
        <br>
        <label for="price">Prix :</label>
        <input type="text" name="price" required>
        <br>
        <label class="form-label">Image</label>
        <input type="file" class="form-control" name="img_url">

        <br>
        <label for="description">Description :</label>
        <textarea name="description" required></textarea>
        <br>
        <input type="submit" name="envoi" value="Ajouter le produit">
    </form>

    <!-- Back button to return to admin home page -->
    <a href="Accueil_admin.php" class="back-button">Retour</a>
</body>
</html>
