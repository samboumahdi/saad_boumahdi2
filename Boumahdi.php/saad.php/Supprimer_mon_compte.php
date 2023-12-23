<?php
include('config.php');

// Assurez-vous que la session est démarrée.
session_start();

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Utilisez la même variable $conn pour la préparation de la requête SELECT
    $recupUser = mysqli_prepare($conn, 'SELECT * FROM user WHERE id = ?');
    mysqli_stmt_bind_param($recupUser, 'i', $userId);
    mysqli_stmt_execute($recupUser);
    mysqli_stmt_store_result($recupUser);

    if (mysqli_stmt_num_rows($recupUser) > 0) {
        // Utilisez également la même variable $conn pour la préparation de la requête DELETE
        $supprimerUser = mysqli_prepare($conn, 'DELETE FROM user WHERE id = ?');
        mysqli_stmt_bind_param($supprimerUser, 'i', $userId);
        mysqli_stmt_execute($supprimerUser);

        // Ajoutez ici d'autres actions après la suppression si nécessaire.

        // Déconnectez l'utilisateur après la suppression
        session_destroy();

        echo "Utilisateur supprimé avec succès";

        // Assurez-vous qu'aucun contenu n'a été envoyé avant la redirection
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Rediriger vers la page de connexion.
        header('Location: login.php');
        exit;
    } else {
        echo "Aucun utilisateur n'a été trouvé";
    }

    // Fermez les déclarations
    mysqli_stmt_close($recupUser);
    mysqli_stmt_close($supprimerUser);
} else {
    echo "Utilisateur non connecté";
}

// Fermez la connexion à la base de données
mysqli_close($conn);
?>
