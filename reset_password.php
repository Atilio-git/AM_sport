<?php
session_start();
if ($_SERVER['REQUEST_METHOD']==='POST'){
    include'db_connect.php';

    $email = $_POST['email'];
    $pass = $_POST['new_pass'];
    $confirmation=$_POST['conf_pass'];

    if (strlen($pass) < 8) {
        die("Mot de passe trop court.");
    }
    if ($pass !== $confirmation) {
        die("Les mots de passe ne correspondent pas.");
    }
    
    $hash = password_hash($pass,PASSWORD_BCRYPT);

    $sql="UPDATE clients SET password_hash = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$hash,$email]);

    $message="<div class='alert alert-success'>Mot de passe réinitialiser</div>";
	header('Location: login.php');
        exit();	
  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log_reg.css">
    <title>Document</title>
</head>
<body class="reset-body">
    <div class="reset_container">
        <div class="reset_card">
        <form method="post" action="reset_password.php" >
                <h2>RESET PASSWORD</h2>
                <input type="email"  name="email"  placeholder="Email" required>
                <input type="password" name="new_pass" placeholder="Nouveau mot de pass " >                
                <input type="password" name="conf_pass" placeholder="confirmer le mot de pass " >                
                <input class="submit-btn" type="submit" name="inscription"  placeholder="m'inscrire"  required>

        </form>
	<?php if ($message): ?>
        	<p><?php echo $message; ?></p>
        <?php endif; ?>
        </div>
    
</body>
</html>
