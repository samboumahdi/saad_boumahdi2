
<?php
include('config.php');

// Vérifie si l'identifiant du produit est défini dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $getid = $_GET['id'];

    // Récupère les informations du produit depuis la base de données
    $recupProduit = mysqli_prepare($conn, 'SELECT * FROM product WHERE id = ?');
    mysqli_stmt_bind_param($recupProduit, 'i', $getid);
    mysqli_stmt_execute($recupProduit);
    mysqli_stmt_store_result($recupProduit);

    // Vérifie si un produit avec l'identifiant spécifié existe
    if (mysqli_stmt_num_rows($recupProduit) > 0) {
        // Récupère les détails du produit
        mysqli_stmt_bind_result($recupProduit, $id, $name, $description, $img_url, $quantity, $price);
        mysqli_stmt_fetch($recupProduit);

        // Vérifie si le formulaire est soumis
        if (isset($_POST['valider'])) {
            // Assainit et récupère les valeurs du formulaire
            $name_saisi = htmlspecialchars($_POST['name']);
            $description_saisi = nl2br(htmlspecialchars($_POST['description']));
            $img_url_saisi = htmlspecialchars($_POST['img_url']);
            $quantity_saisi = intval($_POST['quantity']); // En supposant que la quantité est un entier
            $price_saisi = floatval($_POST['price']); // En supposant que le prix est un nombre flottant

            // Met à jour les informations du produit dans la base de données
            $updateProduit = mysqli_prepare($conn, 'UPDATE product SET name = ?, description = ?, img_url = ?, quantity = ?, price = ? WHERE id = ?');
            mysqli_stmt_bind_param($updateProduit, 'sssidi', $name_saisi, $description_saisi, $img_url_saisi, $quantity_saisi, $price_saisi, $getid);
            mysqli_stmt_execute($updateProduit);

            // Redirige vers la page de gestion des produits après la mise à jour réussie
            header('Location: Gestion_produit.php');
            echo "Produit modifié avec succès";
        }
    } else {
        echo "Aucun produit trouvé";
    }

    // Ferme la requête préparée
    mysqli_stmt_close($recupProduit);
} else {
    echo "Aucun identifiant trouvé";
}

// Ferme la connexion à la base de données à la fin du script
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier produit</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier produit</title>
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

        input, textarea, select {
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

        /* Style pour le champ de fichier */
        .form-control {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        /* Ajout de marge en haut pour l'étiquette */
        label.form-label {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <label for="name">Titre :</label>
        <input type="text" name="name" value="<?= isset($name) ? $name : ''; ?>">

        <label for="description">Description :</label>
        <textarea name="description"><?= isset($description) ? $description : ''; ?></textarea>

        <label class="form-label">Image :</label>
        <input type="file" class="form-control" name="img_url">

        <label for="quantity">Quantité :</label>
        <input type="number" name="quantity" value="<?= isset($quantity) ? $quantity : 0; ?>">

        <label for="price">Prix :</label>
        <input type="number" step="0.01" name="price" value="<?= isset($price) ? $price : 0.00; ?>">

        <input type="submit" name="valider">
    </form>
</body>
</html>
