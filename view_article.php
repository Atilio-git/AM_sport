<?php
session_start();

include'db_connect.php';


if(!isset($_SESSION['id'])){
    header('Location: login.php');
    exit();
}


$id_art=$_GET['id_article'];

$stmt = $conn->prepare("SELECT articles.*, infos_articles.prix, infos_articles.taille 
                        FROM articles LEFT JOIN infos_articles ON articles.id_article = infos_articles.id_article WHERE articles.id_article=?");

$stmt->execute([$id_art]);

$art=$stmt->fetch(PDO::FETCH_ASSOC);

$qte=$_POST['quantite']


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log_reg.css">
    <title>Document</title>
</head>
<body>
        <div class="view_card">
                <h3><?php echo $art['nom_article'];?></h3>
                <img src="<?php echo $art['photo_articles'];?>" alt="Produit">
                <p><?php echo $art['nom_categorie'] ;?></p>
                <p><?php echo $art['description'] ;?></p>
                <div>
                    <p><?php echo $art['prix'] ;?>€</p>
                </div>
                <a href="commandes.php" class="btn-commande">Commander</a>
                <form action="panier.php" method="post">
                    <input type="hidden" name="id_article" value="<?php echo $art['id_article']; ?>">
                    <input type="number" name="quantite" placeholder="quantité" min="1" value="1" step="1" max="99">
                    <button type="submit" class="btn-add_panier">Ajouter au panier</button>
                </form>
        </div>
</body>
</html>


