<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$db   = 'ecom1_project';
$user = 'root';
$pass = '';
$port = '3306';
$charset = 'utf8mb4';

// Connexion à la base de données avec MySQLi
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// Vérification de la connexion
if (!$conn) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

// Vérifiez si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérez l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupérez les informations de l'utilisateur à partir de la base de données
$recupUser = mysqli_prepare($conn, 'SELECT * FROM user WHERE id = ?');
mysqli_stmt_bind_param($recupUser, 'i', $user_id);
mysqli_stmt_execute($recupUser);

if (mysqli_stmt_num_rows($recupUser) > 0) {
    mysqli_stmt_bind_result($recupUser, $id, $first_name, $lname, $email, $pwd);

    mysqli_stmt_fetch($recupUser);

    if (isset($_POST['valider'])) {
        // Récupérez les données du formulaire
        $first_name_saisi = htmlspecialchars($_POST['first_name']);
        $lname_saisi = htmlspecialchars($_POST['last_name']);
        $pwd_saisi = htmlspecialchars($_POST['password']);
        $email_saisi = htmlspecialchars($_POST['email']);

        // Mettez à jour les informations de l'utilisateur dans la base de données, le mot de passe est modifié si le champ n'est pas vide
        if (!empty($pwd_saisi)) {
            $pwd_saisi_hash = password_hash($pwd_saisi, PASSWORD_DEFAULT);
            $updateUser = mysqli_prepare($conn, 'UPDATE user SET fname = ?, pwd = ?, email = ?, lname = ? WHERE id = ?');
            mysqli_stmt_bind_param($updateUser, 'ssssi', $first_name_saisi, $pwd_saisi_hash, $email_saisi, $lname_saisi, $user_id);
            mysqli_stmt_execute($updateUser);
        } else {
            $updateUser = mysqli_prepare($conn, 'UPDATE user SET fname = ?, email = ?, lname = ? WHERE id = ?');
            mysqli_stmt_bind_param($updateUser, 'sssi', $first_name_saisi, $email_saisi, $lname_saisi, $user_id);
            mysqli_stmt_execute($updateUser);
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Modifier Profil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <!-- Votre CSS ici -->
    </style>
</head>
<body>
    <?php
    // ... (votre code PHP pour la connexion et la mise à jour des données)

    // Définissez les variables à utiliser dans le formulaire
    $first_name_affiche = isset($first_name_saisi) ? $first_name_saisi : (isset($first_name) ? $first_name : '');
    $lname_affiche = isset($lname_saisi) ? $lname_saisi : (isset($lname) ? $lname : '');
    $email_affiche = isset($email_saisi) ? $email_saisi : (isset($email) ? $email : '');
    ?>

    <form method="POST" action="">
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?= $first_name_affiche; ?>">

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?= $lname_affiche; ?>">
            <input type="password" name="password" value="">

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= $email_affiche; ?>">

            <label for="password">Password:</label>
        </div>

        <input type="submit" name="valider" value="Submit">
    </form>
</body>
</html>
