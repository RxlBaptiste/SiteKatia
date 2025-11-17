<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/../db.php';

// Vérifier si connecté
if (!isset($_SESSION['pseudo'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Méthode non autorisée');
}

if (!isset($_FILES['profil_image']) || $_FILES['profil_image']['error'] !== UPLOAD_ERR_OK) {
    exit('Erreur : aucune image envoyée.');
}

// Sécurisation : formats autorisés
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($_FILES['profil_image']['tmp_name']);

if (!isset($allowed[$mime])) {
    exit("Format non autorisé.");
}

$ext = $allowed[$mime];

// Dossier où stocker l'image
$uploadDir = __DIR__ . '/../images/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Nouveau nom
$filename = 'profil_' . date('Ymd_His') . '.' . $ext;
$path = $uploadDir . $filename;

// Déplacer le fichier
if (!move_uploaded_file($_FILES['profil_image']['tmp_name'], $path)) {
    exit("Impossible d'enregistrer l'image.");
}

// Chemin utilisé sur le site
$relativePath = 'images/' . $filename;

// Mise à jour BDD
$sql = "UPDATE company_profile SET profil_image = :img LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([':img' => $relativePath]);

header('Location: profil.php?success=1');
exit;