<?php 

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();


$message="";
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    include 'db_connect.php';

    $nom_art = $_POST['nom_article'];
    $photo = $_POST['photo_articles'];
    $description = $_POST['description'];
    $categorie =$_POST['nom_categorie'];
    $prix=$_POST['prix'];

    $sql1= "INSERT INTO  articles (nom_article,photo_articles,description,nom_categorie) VALUES (?,?,?,?)";
    $stmt1 =$conn->prepare($sql1);
    $stmt1->execute([$nom_art, $photo, $description, $categorie]);

    

    $id_art=$conn->lastInsertId();
    
    if (isset($_POST['taille'])) {
        $tailles = $_POST['taille'];
    } else {
    $tailles = [];
    }
    $tailles_str = implode(',', $tailles);

    $sql3="INSERT INTO infos_articles (id_article,nom_article,taille,prix) VALUES (?,?,?,?) ";
    $stmt3= $conn->prepare($sql3);
    $stmt3->execute([$id_art,$nom_art,$tailles_str,$prix]);

    $message='Article ajouté avec succès';
	header('Location: panneau_admin.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log_reg.css">
    <title>Ajout de Produit</title>
</head>
<body class="add_body">
    <div class="add_container">
    <form action="add_article.php" method="post">

        <h2>AJOUTER UN NOUVEL ARTICLE</h2>
        <input type="text" name="nom_article" placeholder="Nom de votre article" required>
        <input type="url" name="photo_articles" placeholder="url de la photo importer" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="text" name="nom_categorie" placeholder="Categorie de l'article (Football /Basketball /Tennis)" required>
        <input type="number" name="prix" placeholder="Prix">
        <div class="taille" aria-required="true">
            <input type="checkbox" name="taille[]" value="M" >M
            <input type="checkbox" name="taille[]" value="S">S
            <input type="checkbox" name="taille[]" value="L">L
            <input type="checkbox" name="taille[]" value="Xl">Xl
            <input type="checkbox" name="taille[]" value="XXL">XXL
        </div>
        
        <input class="submit-btn" type="submit" name="envoyer" value= "Poster">
        
    </form>
    <?php if ($message): ?>
                <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
