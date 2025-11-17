<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/../../db.php';

// Sécurité admin
if (!isset($_SESSION['pseudo'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Méthode non autorisée');
}

// Config commune
$allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];

$finfo = new finfo(FILEINFO_MIME_TYPE);
$uploadDir = __DIR__ . '/images/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Inputs à gérer : champ du formulaire → nom logique + préfixe de fichier
$fields = [
    'img_naturel' => [
        'name'   => 'naturel',              // valeur dans la colonne `name`
        'prefix' => 'maquillage_naturel_',
    ],
    'img_sophistique' => [
        'name'   => 'sophistique',
        'prefix' => 'maquillage_sophistique_',
    ],
];

foreach ($fields as $inputName => $config) {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
        // pas d'image envoyée pour ce champ → on passe
        continue;
    }

    if ($_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        exit("Erreur à l’upload pour {$inputName}");
    }

    $mime = $finfo->file($_FILES[$inputName]['tmp_name']);
    if (!isset($allowed[$mime])) {
        exit("Format d’image non autorisé pour {$inputName}");
    }

    $ext = $allowed[$mime];

    // Nom de fichier
    $filename = $config['prefix'] . date('Ymd_His') . '.' . $ext;
    $target   = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES[$inputName]['tmp_name'], $target)) {
        exit("Impossible d’enregistrer l’image pour {$inputName}");
    }

    $relativePath = 'images/' . $filename;

    // INSERT ou UPDATE de la ligne correspondante
    $sql = "
        INSERT INTO maquillage_examples (name, image_path)
        VALUES (:name, :path)
        ON DUPLICATE KEY UPDATE image_path = VALUES(image_path)
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $config['name'],
        ':path' => $relativePath,
    ]);
}

header('Location: maquillage.php?updated=1');
exit;