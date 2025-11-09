<?php
require __DIR__ . '../../../db.php';


if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  http_response_code(405);
  exit('Méthode non autorisée');
}

$id    = (int)($_POST['id'] ?? 0);
$label = trim($_POST['label'] ?? '');
$price = ($_POST['price'] === '' ? 0 : (int)$_POST['price']); // 0 = Sur devis

if ($id <= 0 || $label === '') {
  http_response_code(400);
  exit('Champs requis manquants.');
}

$stmt = $pdo->prepare("
  UPDATE `services`
  SET `label` = :label, `price` = :price
  WHERE `id` = :id
  LIMIT 1
");

$stmt->execute([
  ':label' => $label,
  ':price' => $price,
  ':id'    => $id,
]);

/* retrouver la section via la catégorie */
$st = $pdo->prepare("SELECT c.type FROM services s JOIN categories c ON c.id = s.category_id WHERE s.id = :id");
$st->execute([':id'=>$id]);
$section = $st->fetchColumn() ?: 'maquillage';

header('Location: changeTarifs.php?section='.$section.'&updated=1');
exit;