<?php
session_start();
require __DIR__ . "/../db.php";

/* Exemple de protection simple (à adapter à ton système):
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: /login.php");
    exit();
}
*/

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $sizes = $_POST['size_label'] ?? []; // tableaux parallèles
    $prices = $_POST['price'] ?? [];

    if ($name === '') $errors[] = "Nom requis";

    // Validation tailles/prix
    $rows = [];
    if (is_array($sizes) && is_array($prices)) {
        for ($i = 0; $i < count($sizes); $i++) {
            $label = trim($sizes[$i] ?? '');
            $price = (float)($prices[$i] ?? 0);
            if ($label !== '' && $price > 0) {
                $rows[] = ['label' => $label, 'price' => $price];
            }
        }
    }
    if (empty($rows)) $errors[] = "Ajoute au moins une taille avec un prix > 0";

    // Upload image (optionnel mais recommandé)
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['image/jpeg','image/png','image/webp'];
        if (!in_array($_FILES['image']['type'], $allowed)) {
            $errors[] = "Image: formats acceptés jpeg/png/webp";
        } else {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $filename = uniqid('prod_', true) . "." . $ext;
            $dir = __DIR__ . "/../uploads";
            if (!is_dir($dir)) mkdir($dir, 0775, true);
            $target = $dir . "/" . $filename;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $errors[] = "Échec de l'upload d'image";
            } else {
                $imagePath = "/uploads/" . $filename; // chemin public
            }
        }
    }

    if (empty($errors)) {
        // 1) Insert produit
        $sql = "INSERT INTO products (name, description, image_path, active, created_at)
                VALUES (?, ?, ?, 1, NOW())";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) die("Prepare error: " . $mysqli->error);
        $stmt->bind_param("sss", $name, $desc, $imagePath);
        $stmt->execute();
        $product_id = $stmt->insert_id;

        // 2) Insert tailles/prix
        $sql2 = "INSERT INTO product_sizes (product_id, size_label, price) VALUES (?, ?, ?)";
        $stmt2 = $mysqli->prepare($sql2);
        if (!$stmt2) die("Prepare2 error: " . $mysqli->error);

        foreach ($rows as $r) {
            $stmt2->bind_param("isd", $product_id, $r['label'], $r['price']);
            $stmt2->execute();
        }

        // PRG pattern (Post/Redirect/Get)
        header("Location: /admin/add_product.php?ok=1");
        exit();
    }
}

$success = isset($_GET['ok']) && $_GET['ok'] == '1';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Ajouter un produit</title>
  <style>
    .row { display:flex; gap:8px; margin-bottom:8px; }
    .row input[type=text], .row input[type=number] { width: 180px; }
    .sizes { margin-top:10px; }
    button.add { margin-top:6px; }
  </style>
</head>
<body>
  <h1>Ajouter un produit</h1>

  <?php if ($success): ?>
    <p style="color:green;">Produit ajouté avec succès.</p>
  <?php endif; ?>

  <?php foreach ($errors as $e): ?>
    <p style="color:red;"><?= htmlspecialchars($e) ?></p>
  <?php endforeach; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Nom du produit</label><br>
    <input type="text" name="name" required><br><br>

    <label>Description</label><br>
    <textarea name="description" rows="4"></textarea><br><br>

    <label>Image (jpeg/png/webp)</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <div class="sizes">
      <strong>Tailles & prix</strong>
      <div id="sizesContainer">
        <div class="row">
          <input type="text" name="size_label[]" placeholder="Taille (ex: S, M, 42)">
          <input type="number" step="0.01" name="price[]" placeholder="Prix (€)">
          <button type="button" onclick="removeRow(this)">Suppr</button>
        </div>
      </div>
      <button class="add" type="button" onclick="addRow()">+ Ajouter une taille</button>
    </div>

    <br><button type="submit">Publier le produit</button>
  </form>

  <script>
    function addRow() {
      const cont = document.getElementById('sizesContainer');
      const div = document.createElement('div');
      div.className = 'row';
      div.innerHTML = `
        <input type="text" name="size_label[]" placeholder="Taille (ex: S, M, 42)">
        <input type="number" step="0.01" name="price[]" placeholder="Prix (€)">
        <button type="button" onclick="removeRow(this)">Suppr</button>
      `;
      cont.appendChild(div);
    }
    function removeRow(btn) {
      const row = btn.parentNode;
      row.parentNode.removeChild(row);
    }
  </script>
</body>
</html>