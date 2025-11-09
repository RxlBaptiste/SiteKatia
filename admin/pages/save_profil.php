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

$id = 1; // ligne unique

$stmt = $pdo->prepare("
UPDATE company_profile
SET company_name=:n,
legal_status=:ls,
siret=:s,
phone=:ph,
address_line1=:a1,
postal_code=:pc,
city=:ct,
country=:co,
host_name=:hn,
host_address=:ha,
host_website=:hw
WHERE id=1
");

$stmt->execute([
':n' => $_POST['company_name'],
':ls' => $_POST['legal_status'],
':s' => preg_replace('/\D+/', '', $_POST['siret']),
':ph' => $_POST['phone'],
':a1' => $_POST['address_line1'],
':pc' => $_POST['postal_code'],
':ct' => $_POST['city'],
':co' => $_POST['country'],
':hn' => $_POST['host_name'],
':ha' => $_POST['host_address'],
':hw' => $_POST['host_website'],
]);

header('Location: contact.php?success=1');
exit;