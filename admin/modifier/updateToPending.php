<?php
// auth.php
declare(strict_types=1);
session_start();
require __DIR__ . '../../db.php';

// Si pas connecté → renvoyer vers la page du formulaire (index.html)
if (!isset($_SESSION['pseudo'])) {
    header('Location: ../index.php'); 
    exit;
}
declare(strict_types=1);
require_once "../db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Méthode non autorisée');
}

$action = $_POST['action'] ?? '';
if ($action !== 'update_avis_pending') {
    http_response_code(400);
    exit('Action invalide');
}

$id    = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$stars = filter_input(INPUT_POST, 'stars', FILTER_VALIDATE_INT);
$text  = isset($_POST['pending_avis']) ? trim((string)$_POST['pending_avis']) : '';

if (!$id || $id < 1) {
    exit('ID manquant ou invalide.');
}
if ($stars === false || $stars < 1 || $stars > 5) {
    exit('Le nombre d’étoiles doit être entre 1 et 5.');
}
if ($text === '') {
    exit('Le texte de l’avis est obligatoire.');
}
if (mb_strlen($text) > 1500) {
    exit('L’avis dépasse 1500 caractères.');
}

// 🔹 Mise à jour uniquement du pending
$sql = "UPDATE avis 
        SET stars = :stars, pending_avis = :txt 
        WHERE id = :id 
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':stars' => $stars,
    ':txt'   => $text,
    ':id'    => $id,
]);

// Redirection après succès
header('Location: avis.php');
exit;