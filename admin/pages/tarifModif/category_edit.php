<?php
require __DIR__ . '../../../db.php';


if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  http_response_code(405);
  exit('Méthode non autorisée');
}

$id   = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');

if ($id <= 0 || $name === '') {
  http_response_code(400);
  exit('Champs requis manquants.');
}

// Génère un slug si vide
if ($slug === '') {
  $slug = strtolower(preg_replace('/\s+/', '-', $name));
}

$type = $_POST['type'] ?? '';
if ($type === '') {
  $st = $pdo->prepare("SELECT `type` FROM `categories` WHERE `id` = :id LIMIT 1");
  $st->execute([':id' => $id]);
  $type = $st->fetchColumn() ?: 'maquillage';
}

// Mise à jour
$stmt = $pdo->prepare("
  UPDATE `categories`
  SET `name` = :name, `slug` = :slug
  WHERE `id` = :id
  LIMIT 1
");
$stmt->execute([
  ':name' => $name,
  ':slug' => $slug,
  ':id'   => $id,
]);

header('Location: changeTarifs.php?section='.$type);
exit;