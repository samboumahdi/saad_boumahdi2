<?php
// Inclusion du fichier de configuration
include('config.php');

// Vérifie si l'identifiant est défini et non vide dans la requête GET
if(isset($_GET['id']) AND !empty($_GET['id'])){
    // Récupère l'identifiant depuis la requête GET
    $getid = $_GET['id'];

    // Prépare la requête pour récupérer les informations du produit avec l'identifiant spécifié
    $recupProduit = mysqli_prepare($conn, 'SELECT * FROM product WHERE id = ?');
    mysqli_stmt_bind_param($recupProduit, 'i', $getid);
    mysqli_stmt_execute($recupProduit);
    mysqli_stmt_store_result($recupProduit);

    // Vérifie si le produit a été trouvé
    if(mysqli_stmt_num_rows($recupProduit) > 0){
        // Prépare la requête pour supprimer le produit avec l'identifiant spécifié
        $deleteProduit = mysqli_prepare($conn, 'DELETE FROM product WHERE id = ?');
        mysqli_stmt_bind_param($deleteProduit, 'i', $getid);
        mysqli_stmt_execute($deleteProduit);

        // Redirige vers la page de gestion des produits après la suppression
        header('Location: Gestion_produit.php');
        exit();
    }else{
        echo "Aucun produit trouvé";
    }

    // Ferme la requête préparée
    mysqli_stmt_close($recupProduit);
}else{
    echo "Aucun identifiant trouvé";
}

// Ferme la connexion à la base de données à la fin du script
mysqli_close($conn);
?>
