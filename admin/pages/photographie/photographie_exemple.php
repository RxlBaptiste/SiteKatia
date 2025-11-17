<!DOCTYPE html>
<html lang="fr">

<head>
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

<body>
    <h2>Images – Maquillage naturel / sophistiqué</h2>

    <form action="update_photo_examples.php" method="post" enctype="multipart/form-data">
        <section class="card">
            <div class="field">
                <label for="img_naturel">Image photo portrait :</label><br>
                <input type="file" name="img_naturel" id="img_naturel" accept="image/*">
            </div>

            <div class="field" style="margin-top: 1rem;">
                <label for="img_sophistique">Image photo intimiste :</label><br>
                <input type="file" name="img_sophistique" id="img_sophistique" accept="image/*">
            </div>

            <button class="btn btn-wide" type="submit" style="margin-top: 1rem;">Mettre à jour</button>
        </section>
    </form>
</body>

</html>