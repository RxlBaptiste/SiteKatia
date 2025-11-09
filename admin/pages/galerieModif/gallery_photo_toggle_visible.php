<?php
require __DIR__ . '../../../db.php';

$id = (int)($_POST['id'] ?? 0);
$pdo->prepare("UPDATE gallery_photos SET visible = 1 - visible WHERE id = :id")->execute([':id'=>$id]);


header('Location: changeGalerie.php?section='.$section.'&replaced=1');