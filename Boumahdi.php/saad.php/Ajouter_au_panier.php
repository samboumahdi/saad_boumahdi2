<?php
include('config.php');
// Démarrer la session pour gérer les informations de l'utilisateur connecté
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    // Récupération des données du formulaire
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['quantity'];
    $product_price = $_POST['price'];
    $userId = $_SESSION['user_id'];

    // Vérifier si le produit est déjà ajouté au panier
    $sql = "SELECT * FROM order_has_product WHERE order_id = ? AND product_id = ?";
    $produitExisteDansPanier = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($produitExisteDansPanier, 'ii', $userId, $product_id);
    mysqli_stmt_execute($produitExisteDansPanier);
    mysqli_stmt_store_result($produitExisteDansPanier);

    if (mysqli_stmt_num_rows($produitExisteDansPanier) > 0) {
        // Mettre à jour la quantité du produit existant dans le panier
        $sql = "UPDATE order_has_product SET quantity = quantity + ? 
                WHERE order_id = ? AND product_id = ?";
        $updateQuantity = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($updateQuantity, 'iii', $product_quantity, $userId, $product_id);
        mysqli_stmt_execute($updateQuantity);
    } else {
        // Ajouter le produit au panier en utilisant le user id comme order id, jusqu'à ce que la commande soit validée
        // Après que le client valide son achat, le panier est vidé, c'est-à-dire que les éléments dans la table order_has_product
        // avec les id du client, seront supprimés.
        $insertCartItem = mysqli_prepare($conn, 'INSERT INTO order_has_product (product_id, quantity, price, order_id)
        VALUES (?, ?, ?, ?)');
        mysqli_stmt_bind_param($insertCartItem, 'iiii', $product_id, $product_quantity, $product_price, $userId);
        mysqli_stmt_execute($insertCartItem);
    }
    echo 'Produit ajouté au panier avec succès!';
} else {
    echo 'Requête invalide, produit non-ajouté au panier.';
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Résultat</title>
</head>
<body>

<!-- Ajouter un bouton de retour vers la page précédente -->
<a href="Accueil_liste_produit.php">Retour à la liste des produits</a>

</body>
</html>
