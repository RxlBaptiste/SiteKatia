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

$profile = $pdo->query("SELECT * FROM company_profile WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="robots" content="noindex, nofollow">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
/* ===== Profil entreprise – style "tarifs" ===== */
:root {
    --bg: #f3f4f0;
    --panel: #fff;
    --border: #d6d6d6;
    --text: #222;
    --muted: #6b7b80;
    --radius: 14px;
    --shadow: 0 6px 18px rgba(0, 0, 0, .12);
    --edit: #a2b29f;
    /* vert sauge */
    --edit-dark: #8b9b86;
    --danger: #d87b7b;
    /* corail */
    --danger-dark: #b96a6a;
}

body.profile {
    margin: 0;
    background: var(--bg);
    color: var(--text);
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, "Helvetica Neue", Helvetica, sans-serif;
}

.page {
    max-width: 980px;
    margin: 24px auto 48px;
    padding: 0 16px;
}

.page-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.page-title {
    font-weight: 700;
    font-size: clamp(18px, 2.4vw, 24px);
}

.whoami {
    font-size: 14px;
    color: var(--muted);
}

.card {
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 18px;
    margin-bottom: 16px;
}

.card h3 {
    margin: 0 0 12px;
    text-align: left;
    font-weight: 700;
    font-size: 18px;
}

/* grille responsive 2 colonnes (passe à 1 colonne en mobile) */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px 18px;
}

@media (max-width:760px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* champs */
.field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.field label {
    font-weight: 600;
    font-size: 14px;
}

.field input,
.field select,
.field textarea {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: #fff;
    font-size: 14px;
}

.field textarea {
    height: 96px;
    padding: 10px 12px;
    resize: vertical;
}

/* regroupements horizontaux (CP + Ville, etc.) */
.inline {
    display: flex;
    gap: 12px;
}


@media (max-width: 660px) {
    .inline {

        flex-direction: column;
        width: fit-content;
    }
}

.inline>.field {
    flex: 1;
}

/* boutons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 36px;
    padding: 0 16px;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: var(--edit);
    color: #0f1a14;
    font-weight: 700;
    cursor: pointer;
    transition: .15s;
}

.btn:hover {
    background: var(--edit-dark);
}

.btn-ghost {
    background: #fff;
}

.btn-danger {
    background: var(--danger);
    color: #fff;
    border-color: transparent;
}

.btn-danger:hover {
    background: var(--danger-dark);
}

.btn-wide {
    min-width: 140px;
}

.page-foot {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 16px;
}

/* petites aides */
.help {
    color: var(--muted);
    font-size: 12px;
}

.sep {
    height: 8px;
}

body.admin {
    padding-top: 70px;
}

.bandeau_admin {
    position: fixed;
    top: 0;
    left: 0;
    width: -webkit-fill-available;
    height: fit-content;
    display: flex;
    justify-content: space-between;
    padding: 1em;
    padding-bottom: 2em;
    margin-bottom: 1em;
    background-color: #b1b5ae;
    color: #ffffff;
}
</style>

<body class="profile  <?= isset($_SESSION['pseudo']) ? 'admin' : '' ?>">
    <div class="page">
        <header class="page-head">
            <div class="bandeau_admin">
                <section>Profil entreprise</section>
                <section>Conecter en tant que : <?= htmlspecialchars($_SESSION['pseudo'], ENT_QUOTES, 'UTF-8') ?>
                </section>
            </div>
        </header>

        <!-- Bloc 1 : Informations pro -->
        <form id="profile-form" method="post" action="save_profil.php">
            <section class="card">
                <h3>Informations professionnelles</h3>
                <div class="form-grid">
                    <div class="field">
                        <label>Nom de l'entreprise</label>
                        <input type="text" name="company_name"
                            value="<?= htmlspecialchars($profile['company_name']) ?>">
                    </div>
                    <div class="field">
                        <label>Statut juridique</label>
                        <input type="text" name="legal_status"
                            value="<?= htmlspecialchars($profile['legal_status']) ?>">
                    </div>
                    <div class="field">
                        <label>SIRET</label>
                        <input type="text" name="siret" value="<?= htmlspecialchars($profile['siret']) ?>">
                    </div>
                    <div class="field">
                        <label>Téléphone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($profile['phone']) ?>">
                    </div>
                </div>
            </section>

            <!-- Bloc 2 : Adresse -->
            <section class="card">
                <h3>Adresse professionnelle</h3>
                <div class="form-grid">
                    <div class="field" style="grid-column:1 / -1">
                        <label>Adresse ligne 1</label>
                        <input type="text" name="address_line1"
                            value="<?= htmlspecialchars($profile['address_line1']) ?>">
                    </div>
                    <div class="inline" style="grid-column:1 / -1">
                        <div class="field" style="max-width:160px">
                            <label>Code postal</label>
                            <input type="text" name="postal_code"
                                value="<?= htmlspecialchars($profile['postal_code']) ?>">
                        </div>
                        <div class="field">
                            <label>Ville</label>
                            <input type="text" name="city" value="<?= htmlspecialchars($profile['city']) ?>">
                        </div>
                        <div class="field" style="max-width:220px">
                            <label>Pays</label>
                            <input type="text" name="country" value="<?= htmlspecialchars($profile['country']) ?>">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Bloc 3 : Hébergeur -->
            <section class="card">
                <h3>Hébergeur</h3>
                <div class="form-grid">
                    <div class="field">
                        <label>Nom de l'hébergeur</label>
                        <input type="text" name="host_name" value="<?= htmlspecialchars($profile['host_name']) ?>">
                    </div>
                    <div class="field">
                        <label>Site web de l'hébergeur</label>
                        <input type="text" name="host_website"
                            value="<?= htmlspecialchars($profile['host_website']) ?>">
                    </div>
                    <div class="field" style="grid-column:1 / -1">
                        <label>Adresse de l'hébergeur</label>
                        <input type="text" name="host_address"
                            value="<?= htmlspecialchars($profile['host_address']) ?>">
                    </div>
                </div>
            </section>
        </form>
        <form action="update_profil_image.php" method="post" enctype="multipart/form-data">
            <section class="card">
                <label for="profil_image">Choisir une nouvelle photo :</label>
                <input type="file" name="profil_image" id="profil_image" accept="image/*" required>
                <button class="btn btn-wide" type="submit">Mettre à jour</button>
            </section>

        </form>
        <footer class="page-foot">
            <a class="btn btn-ghost" href="contact.php">Retour</a>
            <button class="btn btn-wide" type="submit" form="profile-form">Enregistrer</button>
        </footer>
    </div>
</body>

</html>