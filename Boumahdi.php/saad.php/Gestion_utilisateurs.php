<?php
session_start();

// Inclure le fichier de configuration pour la connexion à la base de données
include('config.php');

// Vérifier la connexion à la base de données
if (!$conn) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

// Récupération du rôle de l'utilisateur en cours
$user_id = $_SESSION['user_id'];
$recupUserRole = mysqli_prepare($conn, 'SELECT role_id FROM user WHERE id = ?');
mysqli_stmt_bind_param($recupUserRole, 'i', $user_id);
mysqli_stmt_execute($recupUserRole);
mysqli_stmt_bind_result($recupUserRole, $userRole);
mysqli_stmt_fetch($recupUserRole);
mysqli_stmt_close($recupUserRole);

// Initialisation de la variable pour éviter une erreur si $recupUtilisateurs n'est pas défini
$recupUtilisateurs = null;

// Si l'utilisateur est un admin (role_id = 2)
if ($userRole == 2) {
    // Récupération des utilisateurs avec un rôle de client (role_id = 3)
    $recupUtilisateurs = mysqli_query($conn, 'SELECT * FROM user WHERE role_id = 3');
    // Si l'utilisateur est un super admin (role_id = 1)
} else if ($userRole == 1) {
    // Récupération de tous les utilisateurs (role_id = 2 et 3)
    $recupUtilisateurs = mysqli_query($conn, 'SELECT * FROM user WHERE role_id != 1');
}

// Le reste de votre code continue ici

// Affichage des utilisateurs
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afficher les utilisateurs</title>
</head>
<body>
    <header>
        <h1>User Management</h1>
    </header>

    <nav>
        <a href="Accueil_admin.php">Retour à l\'Accueil</a>
    </nav>

    <main>';

if ($recupUtilisateurs && mysqli_num_rows($recupUtilisateurs) > 0) {
    while ($utilisateur = mysqli_fetch_assoc($recupUtilisateurs)) {
        echo '<p>
            Username: ' . $utilisateur["user_name"] . '<br>
            Role: ' . ($utilisateur["role_id"] == 2 ? 'Admin' : 'Client') . '<br>
            <a href="Supprimer_utilisateur.php?id=' . $utilisateur['id'] . '" class="btn btn-primary">Supprimer l\'utilisateur</a><br>
            <a href="Change_statut_utilisateur.php?id=' . $utilisateur['id'] . '" class="btn btn-primary">Change_statut_utilisateur</a>
            </p>';
    }
} else {
    echo 'Aucun utilisateur à gérer!';
}

echo '  </main>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>';

// Fermer la connexion à la base de données à la fin du script
mysqli_close($conn);
?>
