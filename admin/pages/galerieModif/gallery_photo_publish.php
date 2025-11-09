<?php
require __DIR__ . '../../../db.php';

$id = (int)($_POST['id'] ?? 0);

$sec = $pdo->prepare("
  SELECT c.type, p.pending_url
  FROM gallery_photos p
  JOIN gallery_categories c ON c.id = p.category_id
  WHERE p.id = :id
");
$sec->execute([':id'=>$id]);
$row = $sec->fetch(PDO::FETCH_ASSOC);
$section = $row['type'] ?? 'photographie';

$pdo->prepare("
  UPDATE gallery_photos
  SET current_url = pending_url, pending_url = NULL
  WHERE id = :id
")->execute([':id'=>$id]);

header('Location: ../'.$section.'/'.$section.'.php');