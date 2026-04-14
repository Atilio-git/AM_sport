<?php
session_start();

include'db_connect.php';


if (isset($_POST['supprimer'])) {
    $id = $_POST['id_article'];
    $conn->prepare("DELETE FROM infos_articles WHERE id_article = ?")->execute([$id]);
    $conn->prepare("DELETE FROM articles WHERE id_article = ?")->execute([$id]);
}

$stmt1=$conn->query("SELECT articles.*, infos_articles.prix, infos_articles.taille 
                        FROM articles LEFT JOIN infos_articles ON articles.id_article = infos_articles.id_article");

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
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom_articles</th>
                <th>Categorie</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Taille</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stmt1 as $art): ?>
            <tr>
                <td><?php echo $art['id_article']; ?></td>
                <td><?php echo $art['nom_article']; ?></td>
                <td><?php echo $art['nom_categorie']; ?></td>
                <td><?php echo $art['description']; ?></td>
                <td><?php echo $art['prix']; ?></td>
                <td><?php echo $art['taille']; ?></td>
                <td class="action_article">
                    <form action="articles.php" method="post">
                        <button type="submit" class="delete_art"  name="supprimer">Supprimer</button> 
                        <a class="update_art" href="update_art.php?id=<?php echo $art['id_article']; ?>">Modifier</a>
                        <input type="hidden" name="id_article" value="<?php echo $art['id_article']; ?>">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
</body>
</html>