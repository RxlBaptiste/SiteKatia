<?php
require __DIR__ . '../../../db.php';

$type = $_POST['type'] ?? 'photographie';
$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');

$ins = $pdo->prepare("
  INSERT INTO gallery_categories (type, name, slug, sort_order)
  VALUES (:type, :name, :slug, 0)
");
$ins->execute([':type'=>$type, ':name'=>$name, ':slug'=>$slug]);

$id = (int)$pdo->lastInsertId();
$pdo->prepare("UPDATE gallery_categories SET sort_order = :id WHERE id = :id")
    ->execute([':id'=>$id]);

header('Location: changeGalerie.php?section='.$type.'&cat_added=1');