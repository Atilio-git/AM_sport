<?php
session_start();
include 'db_connect.php';

// 1. PROTECTION : Vérifier que l'utilisateur est bien un ADMIN
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

try {
    // 2. RÉCUPÉRATION DES COMMANDES + INFOS CLIENTS
    // On joint la table commandes et clients pour avoir les noms
    $query = "
        SELECT c.id_commande, c.date_commande, c.statut, cl.nom_client, cl.prenom, cl.email 
        FROM commandes c
        JOIN clients cl ON c.id_client = cl.id_client
        ORDER BY c.date_commande DESC
    ";
    $stmt = $conn->query($query);
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. STATS RAPIDES (Optionnel mais pro pour l'admin)
    $total_cmd = count($commandes);
} catch (PDOException $e) {
    die("Erreur BDD : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Commandes - Admin</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; padding: 30px; }
        .admin-container { max-width: 1000px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px; }
        h1 { color: #2c3e50; margin: 0; font-size: 24px; }
        
        .stats-badge { background: #534AB7; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; background: #f4f4f9; padding: 15px; color: #666; font-size: 13px; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 14px; }
        tr:hover { background-color: #fcfcfd; }

        /* Badges de statut */
        .status { padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: bold; }
        .status-en-attente { background: #fff3cd; color: #856404; }
        .status-payé { background: #d4edda; color: #155724; }
        .status-livré { background: #e2e3e5; color: #383d41; }

        .client-name { font-weight: 600; color: #333; }
        .client-email { font-size: 12px; color: #888; }
        
        .btn-action { text-decoration: none; color: #534AB7; font-weight: 600; font-size: 13px; border: 1px solid #534AB7; padding: 4px 10px; border-radius: 4px; }
        .btn-action:hover { background: #534AB7; color: white; }
    </style>
</head>
<body>

<div class="admin-container">
    <header>
        <h1>📦 Gestion des Commandes</h1>
        <div class="stats-badge"><?= $total_cmd ?> commandes au total</div>
    </header>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($commandes)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding: 30px; color: #999;">Aucune commande trouvée.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($commandes as $cmd): ?>
                <tr>
                    <td>#<?= str_pad($cmd['id_commande'], 5, "0", STR_PAD_LEFT) ?></td>
                    <td>
                        <div class="client-name"><?= htmlspecialchars($cmd['prenom'] . ' ' . $cmd['nom_client']) ?></div>
                        <div class="client-email"><?= htmlspecialchars($cmd['email']) ?></div>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($cmd['date_commande'])) ?></td>
                    <td>
                        <span class="status status-<?= str_replace(' ', '-', $cmd['statut']) ?>">
                            <?= ucfirst($cmd['statut']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="detail_commande_admin.php?id=<?= $cmd['id_commande'] ?>" class="btn-action">Détails</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        <a href="panneau_admin.php" style="color: #666; text-decoration: none; font-size: 14px;">← Retour au panneau</a>
    </div>
</div>

</body>
</html>
