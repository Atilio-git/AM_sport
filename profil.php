<?php
session_start();
include 'db_connect.php';

// On change 'id_client' par 'id' pour correspondre à ton Array de session
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// On récupère l'ID via la clé qui existe réellement : 'id'
$id_client = $_SESSION['id']; 

try {
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id_client = ?");
    $stmt->execute([$id_client]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die("Erreur : Impossible de trouver le client en BDD avec l'ID " . htmlspecialchars($id_client));
    }
} catch (PDOException $e) {
    die("Erreur BDD : " . $e->getMessage());
}

// ... reste du code pour l'affichage ...
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil - <?= htmlspecialchars($client['prenom']) ?></title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 20px; color: #1c1e21; }
        .container { max-width: 500px; margin: 50px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        .header-bg { height: 120px; background: linear-gradient(135deg, #6e8efb, #a7a7af); }
        .profile-content { padding: 20px; text-align: center; position: relative; }
        .avatar { 
            width: 100px; height: 100px; background: #007bff; color: white; 
            font-size: 40px; font-weight: bold; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            margin: -70px auto 15px; border: 5px solid white;
        }
        .info-section { text-align: left; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .label { color: #65676b; font-weight: 600; }
        .value { color: #1c1e21; }
        .btn-group { margin-top: 30px; display: flex; flex-direction: column; gap: 10px; }
        .btn { padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px; transition: 0.2s; }
        .btn-primary { background: #007bff; color: white; }
        .btn-outline { border: 1px solid #ddd; color: #333; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-bg"></div>
    <div class="profile-content">
        <div class="avatar"><?= $initiales ?></div>
        <h2 style="margin:0;"><?= htmlspecialchars($client['prenom'] . ' ' . $client['nom_client']) ?></h2>
        <p style="color: #65676b; margin: 5px 0;"><?= htmlspecialchars($client['email']) ?></p>

        <div class="info-section">
            <div class="info-row">
                <span class="label">Rôle utilisateur :</span>
                <span class="value" style="text-transform: capitalize;"><?= htmlspecialchars($client['role']) ?></span>
            </div>
            <div class="info-row">
                <span class="label">Total commandes :</span>
                <span class="value"><?= (int)$stats['total_commandes'] ?></span>
            </div>
        </div>

        <div class="btn-group">
            <a href="historique_commandes.php" class="btn btn-primary">📦 Voir mes commandes</a>
            <a href="modifier_profil.php" class="btn btn-outline">✎ Modifier mes infos</a>
            <a href="logout.php" class="btn btn-outline" style="color: #d93025; border-color: #f8d7da;">Se déconnecter</a>
        </div>
    </div>
</div>

</body>
</html>
