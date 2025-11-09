<?php

require __DIR__ . '../../../db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Méthode non autorisée');
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    exit('ID de service manquant ou invalide.');
}

$st = $pdo->prepare("SELECT c.type FROM gallery_photos s JOIN gallery_categories c ON c.id = s.category_id WHERE s.id = :id");
$st->execute([':id'=>$id]);
$section = $st->fetchColumn() ?: 'maquillage';

$stmt = $pdo->prepare("DELETE FROM `gallery_photos` WHERE `id` = :id LIMIT 1");
$stmt->execute([':id' => $id]);

    header('Location: changeGalerie.php?section='.$section.'&deleted=1');
    exit;


?>