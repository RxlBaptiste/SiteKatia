<?php

require __DIR__ . '../../../db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Méthode non autorisée');
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    exit('ID de catégorie manquant ou invalide.');
}

$cur = $pdo->prepare("SELECT type FROM gallery_categories WHERE id=:id");
$cur->execute([':id'=>$id]);
$type = $cur->fetchColumn() ?: 'maquillage';

$check = $pdo->prepare("SELECT COUNT(*) FROM gallery_photos WHERE category_id = :id");
$check->execute([':id' => $id]);
if ($check->fetchColumn() > 0) {
    $PHPtext = "Impossible : des services existent dans cette catégorie.";
echo "<script type='text/javascript'>var JavaScriptAlert = " . json_encode($PHPtext) . "; alert(JavaScriptAlert);</script>";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM `gallery_categories` WHERE `id` = :id LIMIT 1");
$stmt->execute([':id' => $id]);


header('Location: changegaleries.php?section='.$type); // adapte le chemin si besoin
exit;