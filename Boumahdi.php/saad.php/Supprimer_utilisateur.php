<?php
session_start();
include('config.php');

// Vérifie si l'identifiant est défini et non vide dans la requête GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Récupère l'identifiant depuis la requête GET
    $getid = $_GET['id'];

    // Récupère les informations de l'utilisateur avec l'identifiant spécifié
    $recupUserSql = "SELECT * FROM user WHERE id = ?";
    $recupUserStmt = mysqli_prepare($conn, $recupUserSql);
    mysqli_stmt_bind_param($recupUserStmt, 'i', $getid);
    mysqli_stmt_execute($recupUserStmt);

    // Vérifie si l'utilisateur a été trouvé
    $recupUserResult = mysqli_stmt_get_result($recupUserStmt);

    if (mysqli_num_rows($recupUserResult) > 0) {
        // Prépare la requête pour supprimer l'utilisateur avec l'identifiant spécifié
        $supprimerUserSql = "DELETE FROM user WHERE id = ?";
        $supprimerUserStmt = mysqli_prepare($conn, $supprimerUserSql);
        mysqli_stmt_bind_param($supprimerUserStmt, 'i', $getid);
        mysqli_stmt_execute($supprimerUserStmt);

        // Redirige vers la page de gestion des utilisateurs après la suppression
        header('Location: Gestion_utilisateurs.php');
        exit; // Arrête l'exécution après la redirection
    } else {
        echo "Aucun membre n'a été trouvé";
    }
} else {
    echo "L'identifiant n'a pas été récupéré";
}
?>
