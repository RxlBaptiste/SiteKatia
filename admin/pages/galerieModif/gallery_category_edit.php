<?php
require __DIR__ . '../../../db.php';

$id   = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
$type = $_POST['type'] ?? ''; // hidden dans la modale

if ($type === '') {
  $t = $pdo->prepare("SELECT type FROM gallery_categories WHERE id=:id");
  $t->execute([':id'=>$id]);
  $type = $t->fetchColumn() ?: 'photographie';
}

$upd = $pdo->prepare("
  UPDATE gallery_categories SET name=:n, slug=:s WHERE id=:id
");
$upd->execute([':n'=>$name, ':s'=>$slug, ':id'=>$id]);

header('Location: changeGalerie.php?section='.$type.'&cat_updated=1');