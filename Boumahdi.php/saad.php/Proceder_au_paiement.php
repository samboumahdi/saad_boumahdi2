<?php

include("config.php");
session_start();
$userId = $_SESSION['user_id'];

// Vérifiez si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez les détails de la carte de crédit depuis le formulaire
    $cardNumber = $_POST['card_number'];
    $cardHolder = $_POST['card_holder'];
    $expiryMonth = $_POST['expiry_month'];
    $expiryYear = $_POST['expiry_year'];
    $cvv = $_POST['cvv'];

    // Traitez le paiement (Dans un scénario réel, vous utiliseriez une API de passerelle de paiement)

    // Pour simplifier, supposons que le paiement est réussi
    $paymentSuccess = true;

    // Calculez le total (vous pouvez avoir cette valeur à partir de votre logique existante)
    $totalCartPrice = 100; // Remplacez par votre logique réelle

    if ($paymentSuccess) {
        // Insérez les détails de la commande dans la base de données
        $orderRef = uniqid(); // Générer une référence unique (vous pourriez avoir besoin d'une meilleure approche)
        $orderDate = date('Y-m-d H:i:s'); // Date et heure actuelles

        // Insérez la commande dans la base de données
        $insertOrderSql = "INSERT INTO user_order (ref, date, total, user_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertOrderSql);
        mysqli_stmt_bind_param($stmt, 'ssdi', $orderRef, $orderDate, $totalCartPrice, $userId);
        mysqli_stmt_execute($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement par carte de crédit</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Détails de paiement (test)</h2>

        <?php if(isset($paymentSuccess) && $paymentSuccess) { ?>
            <!-- Affichez un message de succès si le paiement est réussi -->
            <div class="alert alert-success">
                Paiement réussi !
            </div>
        <?php } ?>

        <form action="Soumission_Commande.php" method="post">
            <div class="form-group">
                <label for="card_number">Numéro de carte</label>
                <input type="text" name="card_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="card_holder">Nom du titulaire de la carte</label>
                <input type="text" name="card_holder" class="form-control" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="expiry_month">Mois d'expiration</label>
                    <input type="text" name="expiry_month" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="expiry_year">Année d'expiration</label>
                    <input type="text" name="expiry_year" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" name="cvv" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Payer maintenant</button>
        </form>
    </div>

    <!-- Scripts JavaScript nécessaires pour Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
