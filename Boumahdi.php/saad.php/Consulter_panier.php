<?php
include('config.php');
// Démarrer la session pour gérer les informations de l'utilisateur connecté
session_start();

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Sélectionnez les produits associés à l'`order_id` de l'utilisateur connecté
    $sql = "SELECT o.order_id as order_id, p.id as product_id, p.name, p.price, p.description, o.quantity, o.quantity * p.price as total_price_produit
            FROM order_has_product as o
            INNER JOIN product as p ON o.product_id = p.id
            WHERE o.order_id = ?";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $cartItems = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Calculer le total du panier
    $totalCartPrice = array_sum(array_column($cartItems, 'total_price_produit'));
}

// Mettre à jour la quantité du produit dans le panier
if (isset($_POST['update_quantity'])) {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    $updateSql = "UPDATE order_has_product SET quantity = ? WHERE order_id = ? AND product_id = ?";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($updateStmt, 'iii', $new_quantity, $order_id, $product_id);
    mysqli_stmt_execute($updateStmt);

    // Rediriger pour éviter la soumission multiple du formulaire
    header('Location: Consulter_panier.php');
    exit();
}

// Supprimer un produit du panier
if (isset($_POST['delete_product'])) {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];

    $deleteSql = "DELETE FROM order_has_product WHERE order_id = ? AND product_id = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteSql);
    mysqli_stmt_bind_param($deleteStmt, 'ii', $order_id, $product_id);
    mysqli_stmt_execute($deleteStmt);

    // Rediriger pour éviter la soumission multiple du formulaire
    header('Location: Consulter_panier.php');
    exit();
}

// Vider complètement le panier
if (isset($_POST['clear_cart'])) {
    $clearSql = "DELETE FROM order_has_product WHERE order_id = ?";
    $clearStmt = mysqli_prepare($conn, $clearSql);
    mysqli_stmt_bind_param($clearStmt, 'i', $user_id);
    mysqli_stmt_execute($clearStmt);

    // Rediriger pour éviter la soumission multiple du formulaire
    header('Location: Consulter_panier.php');
    exit();
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter le Panier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Contenu du Panier</h2>

        <?php if(isset($cartItems) && !empty($cartItems)) { ?>
            <form method="post" action="">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom du Produit</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item) { ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['price']; ?></td>
                                <td>
                                    <!-- Formulaire pour mettre à jour la quantité -->
                                    <form method="post" action="">
                                        <input type="hidden" name="order_id" value="<?php echo $item['order_id']; ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <input type="number" name="new_quantity" value="<?php echo $item['quantity']; ?>" min="1" required>
                                        <button type="submit" name="update_quantity" class="btn btn-primary">Mettre à jour</button>
                                    </form>
                                </td>
                                <td><?php echo $item['total_price_produit']; ?></td>
                                <td>
                                    <!-- Formulaire pour supprimer le produit -->
                                    <form method="post" action="">
                                        <input type="hidden" name="order_id" value="<?php echo $item['order_id']; ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <button type="submit" name="delete_product" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total du Panier</strong></td>
                            <td><?php echo $totalCartPrice; ?></td>
                            <td>
                                <!-- Formulaire pour vider le panier -->
                                <form method="post" action="">
                                    <button type="submit" name="clear_cart" class="btn btn-danger">Vider le Panier</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

        <?php } else { ?>
            <p>Votre panier est vide.</p>
        <?php } ?>

        <!-- Lien pour ajouter d'autres produits -->
        <a href="Accueil_liste_produit.php" class="btn btn-primary">Ajouter d'autres produits</a>
        
        <!-- Lien pour passer la commande -->
        <a href="Confirmation_commande.php" class="btn btn-success">Passer la commande</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
