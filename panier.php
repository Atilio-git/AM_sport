<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$id_client = $_SESSION['id'];

// Insertion seulement si on reçoit un POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_art = isset($_POST['id_article']) ? (int)$_POST['id_article'] : 0;
    $qte    = isset($_POST['quantite'])   ? (int)$_POST['quantite']   : 1;

    if ($id_art > 0 && $qte > 0) {
        $stmt = $conn->prepare("SELECT articles.*, infos_articles.prix 
                                FROM articles 
                                LEFT JOIN infos_articles ON articles.id_article = infos_articles.id_article 
                                WHERE articles.id_article = ?");
        $stmt->execute([$id_art]);
        $art = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($art) {
            $nom_article = $art['nom_article'];
            $prix_uni    = $art['prix'];
            $total       = $prix_uni * $qte;

            $stmt_panier = $conn->prepare("INSERT INTO panier (id_client, id_article, nom_article, prix_unitaire, quantite, prix_total) 
                                           VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_panier->execute([$id_client, $id_art, $nom_article, $prix_uni, $qte, $total]);
        }
    }
}

// Toujours récupérer le panier depuis la BDD pour l'affichage
$stmt_affichage = $conn->prepare("SELECT * FROM panier WHERE id_client = ?");
$stmt_affichage->execute([$id_client]);
$articles_panier = $stmt_affichage->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="informations_panier">
    <?php if (empty($articles_panier)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <?php foreach ($articles_panier as $item): ?>
            <div class="item_panier">
                <p><?= $item['nom_article'] ?></p>
                <p><?= $item['quantite'] ?></p>
                <p><?= $item['prix_unitaire'] ?>€</p>
                <p>Total : <?= $item['prix_total'] ?>€</p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>