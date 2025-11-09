<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/db.php'; // ajuste si besoin


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $pseudo = trim($_POST['pseudo'] ?? '');
    $password = $_POST['password'] ?? '';
  
  if ($pseudo === '' || $password === '') {
    http_response_code(400);
    exit('Identifiant ou mot de passe manquant.');
  }
  
    $sql = "SELECT id, pseudo, password FROM user WHERE pseudo = :pseudo LIMIT 1 ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pseudo' => $pseudo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
    if(!$user || !password_verify($password, $user['password'])) {
      http_response_code(401);
      exit('Identifiant ou mot de passe invalide.');
    }
  
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['pseudo'] = $user['pseudo'];
  
    session_regenerate_id(true);
  
    header('Location: modifier/index.php');
    exit;
} else {
  http_response_code(405);
  exit('Méthode non autorisée');
} 