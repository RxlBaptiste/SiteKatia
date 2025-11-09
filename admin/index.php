<?php
// admin/create_first_user.php
require __DIR__ . '/db.php';

// 1) si un user existe déjà -> 403
$already = (int)$pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
if ($already > 0) {
  header('Location: connection.html');
  exit;
}

// 2) POST = création
$errors = [];
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  $pseudo = trim($_POST['pseudo'] ?? '');
  $pass1  = (string)($_POST['password'] ?? '');
  $pass2  = (string)($_POST['password2'] ?? '');

  // validations simples
  if ($pseudo === '' || strlen($pseudo) < 3) {
    $errors[] = "Identifiant trop court (3+).";
  }
  if ($pass1 === '' || strlen($pass1) < 8) {
    $errors[] = "Mot de passe trop court (8+).";
  }
  if ($pass1 !== $pass2) {
    $errors[] = "Les mots de passe ne correspondent pas.";
  }

  if (!$errors) {
    // créer le compte admin
    $hash = password_hash($pass1, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
      "INSERT INTO user (pseudo, password, admin)
       VALUES (:p, :h, 1)"
    );
    $stmt->execute([':p'=>$pseudo, ':h'=>$hash]);

    // redirection vers la page de login
    header('Location: connection.html'); // ou /admin/login.php selon ta route
    exit;
  }
}
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Première installation</title>
    <link rel="stylesheet" href="/admin/modifier/style.css" />
</head>

<style>
/* ===========================
   LOGIN ADMIN — look & feel
   =========================== */

:root {
    --bg: #f6f6f3;
    --panel: #ffffff;
    --ink: #2f2f2f;
    --muted: #6b7b80;
    --border: #e3e5e1;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);

    --brand: #a2b29f;
    /* vert sauge (bouton) */
    --brand-600: #8b9b86;
    --danger: #d87b7b;
    /* erreurs */
    --radius: 16px;
}

html,
body {
    height: 100%;
}

body {
    margin: 0;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial,
        "Helvetica Neue", Helvetica, sans-serif;
    color: var(--ink);
    background: linear-gradient(180deg, #f8faf7 0%, var(--bg) 100%);
    display: grid;
    place-items: center;
}

/* wrapper pour garder un beau centrage sur grands écrans */
.login {
    width: min(960px, 92%);
    margin: auto;
    padding: 24px 0;
}

.login-card {
    margin-inline: auto;
    width: min(380px, 92vw);
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 26px 22px 22px;
}

.login-head {
    text-align: center;
    margin-bottom: 18px;
}

.login-head h1 {
    margin: 0 0 6px 0;
    font-weight: 800;
    font-size: clamp(18px, 2.4vw, 22px);
    letter-spacing: 0.3px;
}

.login-head .sub {
    color: var(--muted);
    font-size: 13px;
}

/* formulaire */
.login form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.field label {
    font-size: 13px;
    color: var(--muted);
}

.field input[type="text"],
.field input[type="password"] {
    height: 42px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: #fff;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.field input:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(162, 178, 159, 0.25);
}

/* ligne options (souvenir / mdp oublié) */
.login-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2px;
    font-size: 13px;
    color: var(--muted);
}

.login-options a {
    color: inherit;
    text-decoration: none;
    border-bottom: 1px dotted currentColor;
}

.login-options a:hover {
    color: var(--ink);
    border-bottom-color: transparent;
}

/* boutons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 42px;
    padding: 0 16px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: #fff;
    cursor: pointer;
    font-weight: 700;
    transition: transform 0.02s ease, background 0.15s, box-shadow 0.15s;
}

.btn:active {
    transform: translateY(1px);
}

.btn-primary {
    background: var(--brand);
    color: #0f1a14;
    border-color: transparent;
}

.btn-primary:hover {
    background: var(--brand-600);
}

/* messages */
.alert {
    margin: 6px 0 2px;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 13px;
    background: #fff5f5;
    color: #7a2d2d;
    border: 1px solid #f1c0c0;
}

/* footer mini */
.login-foot {
    margin-top: 14px;
    text-align: center;
    color: var(--muted);
    font-size: 12px;
}

/* petit + : mode sombre respectueux */
@media (prefers-color-scheme: dark) {
    :root {
        --bg: #111416;
        --panel: #1a1e20;
        --ink: #e9ecef;
        --muted: #9aa6ad;
        --border: #2a2f33;
        --shadow: 0 10px 40px rgba(0, 0, 0, 0.45);
    }

    .field input {
        background: #0f1315;
    }

    .btn {
        background: #0f1315;
        color: var(--ink);
    }

    .btn-primary {
        color: #101312;
    }
}
</style>

<body>
    <div class="login">
        <div class="login-card">
            <div class="login-head">
                <h1>Première installation</h1>
                <div class="sub">Créez votre identifiant administrateur</div>
            </div>

            <?php if ($errors): ?>
            <div class="alert"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
            <?php endif; ?>

            <form method="post" autocomplete="off">
                <div class="field">
                    <label for="pseudo">Identifiant</label>
                    <input id="pseudo" type="text" name="pseudo" required minlength="3" autofocus>
                </div>
                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input id="password" type="password" name="password" required minlength="8">
                </div>
                <div class="field">
                    <label for="password2">Confirmer le mot de passe</label>
                    <input id="password2" type="password" name="password2" required minlength="8">
                </div>
                <button class="btn btn-primary" type="submit">Créer le compte</button>
            </form>
        </div>
    </div>
</body>

</html>