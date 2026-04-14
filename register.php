<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connect.php';
    
    $username = $_POST['nom_client'];
    $prenom   = $_POST['prenom'];
    $email    = $_POST['email'];
    $pass     = $_POST['password_hash'];
    $adresse  = $_POST['adresse_client'];
    $hash     = password_hash($pass, PASSWORD_BCRYPT);

    $sql  = "INSERT INTO clients (email, password_hash, nom_client, prenom, addresse_client) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $hash, $username, $prenom, $adresse]);

    echo"<div class='alert alert-success'>Inscription Réussie</div>";
    header('Location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log_reg.css">
    <title>Register</title>
</head>
<body class="register-body">
    <div class="register_container">
        <div class="register_card">
        <form method="POST" action="register.php" >
                <h2>ENREGISTRER VOUS</h2>
                <input type="text" name="nom_client"  placeholder="Votre nom" required>
                <input type="text"  name="prenom" placeholder="Votre prenom" required>
                <input type="email"  name="email"  placeholder="email" required>
                <input type="password"  name="password_hash"  placeholder="mot de passe" required>
                <input type="text" name="adresse_client"  placeholder="Votre adresse" required>
                <input class="submit-btn" type="submit" name="inscription"  placeholder="m'inscrire"  required>

        </form>
        </div>
        <div class="sous_formulaire">
            <a href="login.php">login</a>
            <a href="reset_password.php">Mot de passe oublié ?</a>
        </div>
    </div>
</body>
