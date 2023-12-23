<?php
// Inclusion du fichier de configuration
include('config.php');

$produits = "";

// Vérifiez si la recherche est soumise
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $sql = "SELECT * FROM product WHERE name LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Vérification de la préparation de la requête
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $search);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $produits = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Fermeture du statement
        mysqli_stmt_close($stmt);
    } else {
        // En cas d'erreur dans la préparation de la requête
        die("Erreur lors de la préparation de la requête : " . mysqli_error($conn));
    }
} else {
    // Si la recherche n'est pas soumise, récupérez tous les produits
    $result = mysqli_query($conn, "SELECT * FROM product");

    // Vérification des erreurs de requête
    if ($result === false) {
        die("Erreur de requête : " . mysqli_error($conn));
    }

    $produits = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Libération des résultats et fermeture de la connexion
    mysqli_free_result($result);
}

// Fermeture de la connexion MySQLi
mysqli_close($conn);
?>
<!doctype html>
<html lang="en">
<head>
    <title>Accueil</title>
</head>
<body>
<div class="container py-2">


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Accueil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product-container {
            border: 1px solid #ddd;
            background-color: #fff;
            margin: 10px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            width: 100%;
        }

        .product-container:hover {
            transform: scale(1.05);
        }

        .product-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .quantity-select {
            width: 50px;
            padding: 5px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <!-- Ajouter le bouton pour consulter le panier -->
    <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <a href="Consulter_panier.php" class="btn btn-primary">Consulter le Panier</a>
                    <a href="accueil_client.php" class="btn btn-secondary">Retour à l'Accueil</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Ajouter un formulaire de recherche -->
    <form method="GET" action="">
        <label for="search">Rechercher un produit par nom :</label>
        <input type="text" name="search" id="search">
        <input type="submit" value="Rechercher">
    </form>

    <div class="container">
        <div class="row">

        <?php
        foreach ($produits as $produit) {
            ?>
            <!-- Afficher ici les informations du produit -->
            <div class="col-md-4">
                <form method="POST" action="Ajouter_au_panier.php">
                    <div class="product-container">
                        <?php if (is_array($produit)) { ?>
                            <!-- Si $produit est un tableau (cas où la recherche n'est pas soumise) -->
                            <img src="<?= "Image_produit/" . $produit['img_url'] ?>" alt="Product Image">
                            <h4>ID: <?php echo $produit['id']; ?></h4>
                            <p>Name: <?php echo $produit['name']; ?></p>
                            <p>Price: <?php echo $produit['price']; ?></p>
                            <p>Description: <?php echo $produit['description']; ?></p>
                            <!-- Ajouter d'autres propriétés du tableau ici -->

                        <?php } elseif (is_object($produit)) { ?>
                            <!-- Si $produit est un objet (cas où la recherche est soumise) -->
                            <img src="<?= "Image_produit/" . $produit->img_url ?>" alt="Product Image">
                            <h4>ID: <?php echo $produit->id; ?></h4>
                            <p>Name: <?php echo $produit->name; ?></p>
                            <p>Price: <?php echo $produit->price; ?></p>
                            <p>Description: <?php echo $produit->description; ?></p>
                            <!-- Ajouter d'autres propriétés de l'objet ici -->

                        <?php } ?>

                        <!-- Ajoutez le champ de sélection pour la quantité -->
                        <label for="quantity">Quantity:</label>
                        <select name="quantity" class="quantity-select">
                            <?php
                            // Vous pouvez ajuster la limite de la quantité en fonction de vos besoins
                            for ($i = 1; $i <= 10; $i++) {
                                echo "<option value=\"$i\">$i</option>";
                            }
                            ?>
                        </select>

                        <!-- Ajoutez des champs cachés pour transmettre les infos du produit -->
                        <input type="hidden" name="price" value="<?php echo is_array($produit) ? $produit['price'] : $produit->price; ?>">
                        <input type="hidden" name="product_id" value="<?php echo is_array($produit) ? $produit['id'] : $produit->id; ?>">
                        <br>
                        <input type="submit" value="Ajouter au panier">
                    </div>
                </form>
            </div>
            <?php
        }
        ?>

            
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>