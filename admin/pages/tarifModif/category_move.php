<?php
require __DIR__ . '../../../db.php';


if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  http_response_code(405); exit('Méthode non autorisée');
}

$id  = (int)($_POST['id'] ?? 0);
$dir = $_POST['dir'] ?? '';
if ($id <= 0 || !in_array($dir, ['up','down'], true)) {
  http_response_code(400); exit('Requête invalide');
}

// Récupère la catégorie courante
$cur = $pdo->prepare("SELECT id, sort_order, type FROM categories WHERE id=:id");
$cur->execute([':id'=>$id]);
$cur = $cur->fetch(PDO::FETCH_ASSOC);
if (!$cur) { exit('Catégorie introuvable'); }
$type = $cur["type"];

$op = ($dir === 'up') ? '<' : '>';
$ord = ($dir === 'up') ? 'DESC' : 'ASC';

// Trouve la voisine (juste au-dessus ou au-dessous)
$neighbor = $pdo->prepare("
  SELECT id, sort_order
  FROM categories
  WHERE type = :type AND sort_order $op :cur
  ORDER BY sort_order $ord
  LIMIT 1
");
$neighbor->execute([':type'=>$type, ':cur' => (int)$cur['sort_order']]);
$neighbor = $neighbor->fetch(PDO::FETCH_ASSOC);

// Si pas de voisine (déjà tout en haut/bas), on ne fait rien
if ($neighbor) {
  $pdo->beginTransaction();
  // Échange des sort_order
  $upd1 = $pdo->prepare("UPDATE categories SET sort_order = :s WHERE id = :id");
  $upd1->execute([':s' => (int)$neighbor['sort_order'], ':id' => (int)$cur['id']]);

  $upd2 = $pdo->prepare("UPDATE categories SET sort_order = :s WHERE id = :id");
  $upd2->execute([':s' => (int)$cur['sort_order'], ':id' => (int)$neighbor['id']]);

  $pdo->commit();
}

header('Location: changeTarifs.php?section='.$type.'&reordered=1');
exit;