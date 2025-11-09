<?php
require __DIR__ . '../../../db.php';

$id  = (int)($_POST['id'] ?? 0);
$dir = $_POST['dir'] ?? 'up';

$cur = $pdo->prepare("SELECT id, sort_order, type FROM gallery_categories WHERE id=:id");
$cur->execute([':id'=>$id]);
$cur = $cur->fetch(PDO::FETCH_ASSOC);
$type = $cur['type'];

$op  = ($dir==='up') ? '<' : '>';
$ord = ($dir==='up') ? 'DESC' : 'ASC';

$nei = $pdo->prepare("
  SELECT id, sort_order
  FROM gallery_categories
  WHERE type = :type AND sort_order $op :cur
  ORDER BY sort_order $ord
  LIMIT 1
");
$nei->execute([':type'=>$type, ':cur'=>$cur['sort_order']]);
$nei = $nei->fetch(PDO::FETCH_ASSOC);

if ($nei) {
  $pdo->beginTransaction();
  $u = $pdo->prepare("UPDATE gallery_categories SET sort_order=:s WHERE id=:i");
  $u->execute([':s'=>$nei['sort_order'], ':i'=>$cur['id']]);
  $u->execute([':s'=>$cur['sort_order'], ':i'=>$nei['id']]);
  $pdo->commit();
}

header('Location: changeGalerie.php?section='.$type.'&reordered=1');