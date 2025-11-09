<?php
require __DIR__ . '../../../db.php';


if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { http_response_code(405); exit; }

$category_id = (int)($_POST['category_id'] ?? 0);
$label = trim($_POST['label'] ?? '');
$price = ($_POST['price'] === '' ? null : (int)$_POST['price']); // null si vide
$section    = $_POST['section'] ?? '';

if ($category_id <= 0 || $label === '') { http_response_code(400); exit('Champs requis.'); }

/* Si la section n’est pas fournie, on la déduit de la catégorie */
if ($section === '') {
  $st = $pdo->prepare("SELECT type FROM categories WHERE id = :id LIMIT 1");
  $st->execute([':id' => $categoryId]);
  $section = $st->fetchColumn() ?: 'maquillage';
}

$price = $price ?? 0; // 0 = Sur devis

$stmt = $pdo->prepare("INSERT INTO services (category_id, label, price, sort_order) VALUES (:c, :l, :p, 0)");
$stmt->execute([':c'=>$category_id, ':l'=>$label, ':p'=>$price]);

// On récupère l'id auto-incrémenté
$lastId = $pdo->lastInsertId();

// Et on le remet dans sort_order
$pdo->prepare("UPDATE services SET sort_order = :id WHERE id = :id")
    ->execute([':id' => $lastId]);


header('Location: changeTarifs.php?section='.$section.'&added=1'); exit;