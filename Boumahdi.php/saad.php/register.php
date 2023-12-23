<?php
// Inclusion du fichier de configuration
include("config.php");
// Initialisation du message d'erreur
$message = '';
// Vérification de la soumission du formulaire
if (isset($_POST['username']) && isset($_POST['password'])) {
    $user_name = $_POST['username'];
    $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];

    // Connexion à la base de données avec MySQLi
    $conn = mysqli_connect($host, $user, $pass, $db, $port);

    // Vérification de la connexion
    if (!$conn) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }

    // Requête SQL pour l'insertion d'un nouvel utilisateur avec MySQLi
    $sql = "INSERT INTO user (email, user_name, pwd, role_id, fname, lname) VALUES (?, ?, ?, 3, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Vérification de la préparation de la requête
    if ($stmt) {
        // Liaison des paramètres et exécution de la requête
        mysqli_stmt_bind_param($stmt, "sssss", $email, $user_name, $pwd, $fname, $lname);
        $result = mysqli_stmt_execute($stmt);

        // Traitement du résultat de la requête
        if ($result) {
            // Affichage d'un message de succès
            echo 'Inscription réussie!';
            // Redirection vers la page de connexion
            header('Location: login.php');
            exit; // Arrêter l'exécution après la redirection
        } else {
            // En cas d'erreur, affichage d'un message d'erreur
            $message = 'Erreur lors de l\'inscription : ' . mysqli_error($conn);
        }

        // Fermeture du statement
        mysqli_stmt_close($stmt);
    } else {
        // En cas d'erreur dans la préparation de la requête
        $message = 'Erreur lors de la préparation de la requête.';
    }

    // Fermeture de la connexion MySQLi
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #333;
        color: #fff;
        padding: 10px;
        text-align: center;
    }

    nav {
        background-color: #007BFF;
        color: #fff;
        padding: 10px;
        text-align: center;
    }

    main {
        max-width: 800px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px 30px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .login-container {
        max-width: 400px;
        margin: 50px auto; /* Ajuster l'espace autour du formulaire de connexion */
        background-color: #fff;
        padding: 20px 30px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h2 {
        margin-top: 0;
        color: #333;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #555;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"] {
        width: 100%;
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
    .buttons-container {
        text-align: center;
        margin-top: 20px; /* Réduire l'espace en haut des boutons */
    }
    .btn {
        margin: 10px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
        display: inline-block; /* Pour que les boutons soient alignés horizontalement */
    }

    .btn:hover {
        background-color: #0056b3;
    }

    p {
        color: red;
        font-weight: bold;
    }

    footer {
        background-color: #333;
        color: #fff;
        padding: 10px;
        text-align: center;
    }
</style>

</head>
<body>

<div class="login-container">
    <h2>Inscription</h2>

    <?php if (!empty($message)): ?>
        <p style="color:red"><?= $message ?></p>
    <?php endif; ?>

    <form action="register.php" method="post">
        <div>
            <label for="email">Adresse e-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username">
        </div>
        <div>
        <label for="fname">fname :</label>
        <input type="text" id="fname" name="fname">
        </div>
        <div>
        <label for="lname">lname :</label>
        <input type="text" id="lname" name="lname">
        </div>
        <div>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">
    
        </div>

        <div>
            <input type="submit" value="S'inscrire">
        </div>
        <div class="buttons-container">
            <a href="index.php" class="btn">Quitter</a>
        </div>

    </form>
</div>

</body>
</html>
