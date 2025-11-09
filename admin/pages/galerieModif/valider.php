<?php
// auth.php
declare(strict_types=1);
session_start();
require __DIR__ . '../../../db.php';

// Si pas connecté → renvoyer vers la page du formulaire (index.html)
/* if (!isset($_SESSION['user'])) {
    header('Location: ../../../index.html'); 
    exit;
}
 */


$action = $_GET['action'] ?? $_POST['action'] ?? null;
$section = $_GET['section'] ?? $_POST['section'] ?? 'maquillage';

if (!$action) {
    die('❌ Aucune action spécifiée.');
}


    
switch ($action) {
    /* === VALIDATION DU CARROUSEL === */
    case 'gallery_photos':
        $sql = "UPDATE gallery_photos SET current_url = pending_url, pending_url = NULL WHERE pending_url IS NOT NULL";
        
        $count = $pdo->exec($sql);
        
        header("Location: ../$section/$section.php");
        exit;

/* ===== Par défaut ===== */
    default:
        die('❌ Type de validation inconnu.');
}
?>