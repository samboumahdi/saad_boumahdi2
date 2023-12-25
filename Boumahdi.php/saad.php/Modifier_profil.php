<?php
include('config.php');

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

$recupUserResult = mysqli_stmt_get_result($recupUser);

if (mysqli_num_rows($recupUserResult) > 0) {
    $userInfos = mysqli_fetch_assoc($recupUserResult);
    $first_name = $userInfos['fname'];
    $lname = $userInfos['lname'];
    $email = $userInfos['email'];

    if (isset($_POST['valider'])) {
        // Récupérez les données du formulaire
        $first_name_saisi = htmlspecialchars($_POST['first_name']);
        $lname_saisi = htmlspecialchars($_POST['last_name']);
        $pwd_saisi = htmlspecialchars($_POST['password']);
        $email_saisi = htmlspecialchars($_POST['email']);

        // Mettez à jour les informations de l'utilisateur dans la base de données, le mot de passe est modifié si le champ n'est pas vide
        if ($pwd_saisi != "") {
            $pwd_saisi_hash = password_hash($pwd_saisi, PASSWORD_DEFAULT);
            $updateUser = mysqli_prepare($conn, 'UPDATE user SET fname = ?, pwd = ?, email = ?, lname = ? WHERE id = ?');
            mysqli_stmt_bind_param($updateUser, 'ssssi', $first_name_saisi, $pwd_saisi_hash, $email_saisi, $lname_saisi, $user_id);
            mysqli_stmt_execute($updateUser);
        } else {
            $updateUser = mysqli_prepare($conn, 'UPDATE user SET fname = ?, email = ?, lname = ? WHERE id = ?');
            mysqli_stmt_bind_param($updateUser, 'sssi', $first_name_saisi, $email_saisi, $lname_saisi, $user_id);
            mysqli_stmt_execute($updateUser);
        }

        // Redirigez l'utilisateur après la mise à jour
        header('Location: Accueil_client.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #00acee;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #00acee;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #007bb5;
        }
    </style>
</head>
<body>
    <header>
        <h1>Modifier Profil</h1>
    </header>

    <form method="POST" action="">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?= isset($first_name) ? $first_name : ''; ?>">

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?= isset($lname) ? $lname : ''; ?>">

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= isset($email) ? $email : ''; ?>">

        <label for="password">Password:</label>
        <input type="password" name="password" value="<?= isset($pwd) ? $pwd : ''; ?>">

        <input type="submit" name="valider" value="Submit">
    </form>
</body>
</html>

