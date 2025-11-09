<?php
declare(strict_types=1);
session_start();
require __DIR__ . '../../../db.php';
require __DIR__ . 'images.php';


if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  http_response_code(405);
  exit('Méthode non autorisée');
}

$id  = (int)($_POST['id'] ?? 0);
$alt = trim($_POST['alt'] ?? '');

if ($id <= 0) {
  http_response_code(400);
  exit('ID invalide.');
}

// Retrouver la section via la catégorie
$q = $pdo->prepare("
  SELECT c.type, p.category_id
  FROM gallery_photos p
  JOIN gallery_categories c ON c.id = p.category_id
  WHERE p.id = :id
");
$q->execute([':id'=>$id]);
$row = $q->fetch(PDO::FETCH_ASSOC);
$section = $row['type'] ?? 'photographie';
$categoryId = (int)($row['category_id'] ?? 0);

try {
  $url = save_image_upload('file', $section, $categoryId);

  $stmt = $pdo->prepare("
    UPDATE gallery_photos
       SET pending_url = :pending, alt = :alt
     WHERE id = :id
     LIMIT 1
  ");
  $stmt->execute([
    ':pending' => $url,
    ':alt'     => $alt,
    ':id'      => $id,
  ]);

  header('Location: changeGalerie.php?section=' . urlencode($section) . '&replaced=1');
  exit;
} catch (Throwable $e) {
  http_response_code(400);
  exit('Remplacement impossible : ' . $e->getMessage());
}