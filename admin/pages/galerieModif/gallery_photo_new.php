<?php
declare(strict_types=1);
session_start();
require __DIR__ . '../../../db.php';
require __DIR__ . '/images.php';


if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  http_response_code(405);
  exit('Méthode non autorisée');
}

$categoryId = (int)($_POST['category_id'] ?? 0);
$section    = $_POST['section'] ?? 'photographie';
$alt        = trim($_POST['alt'] ?? '');

if ($categoryId <= 0) {
  http_response_code(400);
  exit('Catégorie invalide.');
}

try {
  $url = save_image_upload('file', $section, $categoryId); // -> /uploads/gallery/section/catId/fichier.jpg

  // Insert en pending
  $stmt = $pdo->prepare("
    INSERT INTO gallery_photos (category_id, pending_url, alt, sort_order)
    VALUES (:cid, :pending, :alt, 0)
  ");
  $stmt->execute([
    ':cid'     => $categoryId,
    ':pending' => $url,
    ':alt'     => $alt,
  ]);

  $id = (int)$pdo->lastInsertId();
  $pdo->prepare("UPDATE gallery_photos SET sort_order = :id WHERE id = :id")
      ->execute([':id' => $id]);

  header('Location: changeGalerie.php?section=' . urlencode($section) . '&added=1');
  exit;
} catch (Throwable $e) {
  http_response_code(400);
  exit('Upload impossible : ' . $e->getMessage());
}