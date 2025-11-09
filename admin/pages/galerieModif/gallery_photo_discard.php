<?php
require __DIR__ . '../../../db.php';

$id = (int)($_POST['id'] ?? 0);
$sec = $pdo->prepare("
  SELECT c.type FROM gallery_photos p
  JOIN gallery_categories c ON c.id = p.category_id
  WHERE p.id = :id
");
$sec->execute([':id'=>$id]);
$section = $sec->fetchColumn() ?: 'photographie';

$pdo->prepare("UPDATE gallery_photos SET pending_url = NULL WHERE id = :id")
    ->execute([':id'=>$id]);

header('Location: changeGalerie.php?section='.$section.'&discarded=1');