<?php
declare(strict_types=1);
require __DIR__ . '/../admin/db.php'; // <-- selon où est ton fichier; sinon __DIR__ . '/db.php'

$UPLOAD_DIR  = __DIR__ . '/uploads/carousel/'; // chemin disque
$PUBLIC_PATH = '../uploads/carousel/';            // chemin URL (depuis /admin/)

if (!is_dir($UPLOAD_DIR)) {
    if (!mkdir($UPLOAD_DIR, 0775, true) && !is_dir($UPLOAD_DIR)) {
        die('Impossible de créer le dossier upload.');
    }
}

// Vérifier le fichier
if (!isset($_FILES['image']) || ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    die('Aucun fichier reçu');
}

// Sécurité basique
$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$mime = mime_content_type($_FILES['image']['tmp_name']) ?: '';
if (!isset($allowed[$mime])) {
    die('Formats autorisés : JPG/PNG/WebP');
}
if (($_FILES['image']['size'] ?? 0) > 10 * 1024 * 1024) {
    die('Fichier > 10 Mo');
}

// Nom unique + déplacement
$ext  = $allowed[$mime];
$name = 'car_' . bin2hex(random_bytes(6)) . '.' . $ext;
$disk = $UPLOAD_DIR . $name;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $disk)) {
    die('Échec de l’upload');
}

$url = $PUBLIC_PATH . $name; // ce qu’on va stocker en BDD

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    // ✅ on remplace le slot choisi
    $stmt = $pdo->prepare("UPDATE `carousel` SET `pending_image_url` = :u WHERE `id` = :id");
    $stmt->execute([':u' => $url, ':id' => $id]);
} else {
    // Aucun id fourni :
    //  - s'il y a déjà 5 images, on remplace la plus ancienne (id le plus petit)
    //  - sinon on insère une nouvelle ligne
    $count = (int)$pdo->query("SELECT COUNT(*) FROM `carousel`")->fetchColumn();

    if ($count >= 5) {
        $fallbackId = (int)$pdo->query("SELECT `id` FROM `carousel` ORDER BY `id` ASC LIMIT 1")->fetchColumn();
        if ($fallbackId > 0) {
            $stmt = $pdo->prepare("UPDATE `carousel` SET `pending_image_url` = :u WHERE `id` = :id");
            $stmt->execute([':u' => $url, ':id' => $fallbackId]);
        } else {
            // improbable (aucune ligne) : on insère
            $stmt = $pdo->prepare("INSERT INTO `carousel` (`pending_image_url`) VALUES (:u)");
            $stmt->execute([':u' => $url]);
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO `carousel` (`pending_image_url`) VALUES (:u)");
        $stmt->execute([':u' => $url]);
    }
}

header('Location: modifier/carrousel.php');
exit;