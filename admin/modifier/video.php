<?php
// auth.php
declare(strict_types=1);
session_start();
require __DIR__ . '../../db.php';


// Si pas connecté → renvoyer vers la page du formulaire (index.html)
if (!isset($_SESSION['pseudo'])) {
    header('Location: ../index.php'); 
    exit;
}
?><?php
// connexion PDO ici...

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['homepage_video'] ?? '');

    $stmt = $pdo->prepare("
        INSERT INTO settings (setting_key, setting_value)
        VALUES ('homepage_video', :value)
        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
    ");
    $stmt->execute(['value' => $url]);
    header("Location: index.php");
    exit;
}

// Récupération de la valeur actuelle
$stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'homepage_video'");
$stmt->execute();
$currentVideo = $stmt->fetchColumn() ?: '';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Espace administrateur du site Katia Bulimar. Accès réservé.">

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, viewport-fit=cover" />
    <title>Katia Bulimar – Maquillage • Coiffure • Photographie</title>
    <link href="https://fonts.googleapis.com/css2?family=Castoro+Titling&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../style.css" />
    <!-- Polices -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Nunito:wght@300;400;600&display=swap"
        rel="stylesheet" />
</head>
<form method="post">
    <label for="homepage_video">URL de la vidéo YouTube</label>
    <input type="text" id="homepage_video" name="homepage_video"
        value="<?= htmlspecialchars($currentVideo, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;max-width:600px;">
    <button type="submit">Enregistrer</button>
</form>