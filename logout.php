<?php
// 1. On démarre la session pour y avoir accès
session_start();

// 2. On vide toutes les données de la session
session_unset();

// 3. On détruit la session sur le serveur
session_destroy();

// 4. On redirige vers la page de connexion
header("Location: login.php");
exit();
?>
