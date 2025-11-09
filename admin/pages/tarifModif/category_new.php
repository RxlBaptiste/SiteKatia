<?php
require __DIR__ . '../../../db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  http_response_code(405);
  exit('Méthode non autorisée');
}

$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
if ($name === '') {
  http_response_code(400);
  exit('Le nom de la catégorie est requis.');
}

// Génère un slug si vide
if ($slug === '') {
  $slug = strtolower(preg_replace('/\s+/', '-', $name));
}

// 1️⃣ On insère la catégorie avec un sort_order temporaire
$type = $_POST['type'] ?? 'maquillage';
$stmt = $pdo->prepare("
  INSERT INTO categories (name, slug, type, sort_order)
  VALUES (:name, :slug, :type, 0)
");
$stmt->execute([':name'=>$name, ':slug'=>$slug, ':type'=>$type]);
$lastId = $pdo->lastInsertId();
$pdo->prepare("UPDATE categories SET sort_order=:id WHERE id=:id")->execute([':id'=>$lastId]);

header('Location: changeTarifs.php?section='.$type);
exit;