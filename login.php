<?php
session_start();
if ($_SERVER['REQUEST_METHOD']==='POST'){
    include'db_connect.php';

    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM clients WHERE email= ? ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);



    if ($user && password_verify($pass,$user['password_hash'])){
        $_SESSION['id']   = $user['id_client'];
        $_SESSION['nom']  = $user['nom_client'];
        $_SESSION['prenom']  = $user['prenom'];
        $_SESSION['email']  = $user['email'];

	if ($user['role']==='admin'){
             header('Location: panneau_admin.php');
            exit();
        }else{
            header('Location: Accueil.php');
        exit();
        }

        
    }
    else{
         $message='Email ou mot de passe incorrect';;
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log_reg.css">
    <title></title>
</head>

<body class="login-body">
    <div class="login_container">
        <div class="login_card">
            <form action="login.php" method="post">
                <h2></h2>
                <input type="email" name="email" placeholder="email">
                <input type="password"  name="password" placeholder="mot de passe">
                <input class="submit-btn"  type="submit" value="Se connecter">


            </form>
        </div>
   
        <div class="sous_login">
            <a href="reset_password.php">Mot de passe oublié ?</a>
            <a href="register.php">S'inscrire</a>

        </div>
	<?php if ($message): ?>
                <p><?php echo $message; ?></p>
        <?php endif; ?>
     </div>
    
    
</body>
</html>
