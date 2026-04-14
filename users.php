<?php
 session_start();
include 'db_connect.php';


if (isset($_POST['supprimer'])) {
    $id = $_POST['id_client'];
    $conn->prepare("DELETE FROM clients WHERE id_client = ?")->execute([$id]);
}

$stmt = $conn->prepare("SELECT * FROM clients WHERE role=?");
$stmt->execute(['client']);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="log_reg.css">
    <title>users</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
		<th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id_client']; ?></td>
                <td><?php echo $user['nom_client']; ?></td>
                <td><?php echo $user['prenom']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role']; ?></td>
		
                <td class="action_users">
                    <form action="users.php" method="post">
                        <button type="submit" class="delete_user"  name="supprimer">Supprimer</button> 
                        <input type="hidden" name="id_client" value="<?php echo $user['id_client']; ?>">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
</body>
</html>
