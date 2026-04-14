<?php
session_start();

    include'db_connect.php';

    $stmt1 = $conn->query("SELECT articles.*, infos_articles.prix, infos_articles.taille 
                        FROM articles LEFT JOIN infos_articles ON articles.id_article = infos_articles.id_article");
    

    

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AMSPORTS - Accueil</title>

    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;900&display=swap" rel="stylesheet">
</head>

<body>

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <h2 class="logo">AMSPORTS</h2>

        <ul>
            <li><a href="Accueil.html">Accueil</a></li>
            <li><a href="Maillot.adulte.html">Maillots Adultes</a></li>
            <li><a href="Maillot.enfant.html">Maillots Enfants</a></li>
            <li><a href="Accessoires.html">Accessoires</a></li>
            <li><a href="historique.php">Historique</a></li>
	    <li><a href="panier.php">Panier</a></li>
            <li><a href="Aide.html">Aide</a></li>
            <li><a href="profil.php">Profil</a></li>

            
            <li class="logout"><a href="logout.php">Se déconnecter</a></li>
 
            <li class="social">
                <a href="#"><img src="image.instagram.jpg" alt="Instagram"></a>
                <a href="#"><img src="image.x.jpg" alt="X"></a>
            </li>
        </ul>
    </nav>

    
    <header class="hero">
        <div class="overlay"></div>
        <h1 class="hero-title">AMSPORT</h1>
        <p class="hero-subtitle">Découvrez nos nouveautés sportives</p>
        <a class="cta" href="#nouveautes">Voir les nouveautés</a>
    </header>

    <!-- SECTION PRODUITS -->
    <section class="section" id="nouveautes">
        <h2>🔥 Nouveautés</h2>
        <div class="cards">
        <?php  foreach($stmt1 as $art): ?>
            

                <div class="card">
                    <h3><?php echo $art['nom_article'];?></h3>
                    <img src="<?php echo $art['photo_articles'];?>" alt="Produit">
                    <p><?php echo $art['nom_categorie'] ;?></p>
                    <p><?php echo $art['description'] ;?></p>
			 <div>
                            <p><?php echo $art['prix'] ;?>€</p>
                        </div>
                     <a href="view_article.php?id_article=<?php echo $art['id_article']; ?>" class="btn">Voir</a>
		     
                     <a href="panier.php" class="btn-add_panier">Ajouter au panier</a>               
		     
		</div>
                 
           
        <?php endforeach; ?>
	</div>	
    </section>

    
</body>
</html>
