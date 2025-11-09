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
require __DIR__ . '/../db.php'; // connexion DB

$action = $_GET['action'] ?? $_POST['action'] ?? null;

if (!$action) {
    die('❌ Aucune action spécifiée.');
}

switch ($action) {

    
    /* === VALIDATION DU CARROUSEL === */
    case 'carrousel':
        $sql = "UPDATE carousel SET curent_image_url = pending_image_url, pending_image_url = NULL WHERE pending_image_url IS NOT NULL";
        
        $count = $pdo->exec($sql);
        
        header('Location: index.php');
        exit;


    /* === VALIDATION DES AVIS === */
    case 'avis':
    
        $sql = "UPDATE avis SET curent_avis = pending_avis, pending_avis = NULL WHERE pending_avis IS NOT NULL ";
        $count = $pdo->exec($sql);
        header('Location: index.php');
        exit;



/* ===== Par défaut ===== */
    default:
        die('❌ Type de validation inconnu.');
}





?>