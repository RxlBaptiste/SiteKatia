<?php 
// auth.php
declare(strict_types=1);
session_start();
require __DIR__ . '../../../db.php';


// Si pas connecté → renvoyer vers la page du formulaire (index.html)
if (!isset($_SESSION['pseudo'])) {
    header('Location: ../../index.php'); 
    exit;
}


$section = $_GET['section'] ?? 'coiffure';
$allowed = ['maquillage','coiffure','photographie'];
if (!in_array($section, $allowed, true)) $section = 'coiffure';

// Catégories de la section (albums)
$cats = $pdo->prepare("
  SELECT id, name, slug
  FROM gallery_categories
  WHERE type = :t
  ORDER BY sort_order, id
");
$cats->execute([':t' => $section]);

// Prépare la requête des photos d'une catégorie
$photosStmt = $pdo->prepare("
  SELECT id, current_url, pending_url, alt, sort_order, visible
  FROM gallery_photos
  WHERE category_id = :cid
  ORDER BY sort_order, id
");
?>
<!doctype html>
<html lang="fr">

<head>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Espace administrateur du site Katia Bulimar. Accès réservé.">

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modification galerie-<?= htmlspecialchars($section) ?></title>

    <link rel="stylesheet" href="../../../style.css" />
    <style>
    :root {
        --radius: 14px;
        --gap: 16px;
        --border: #d6d6d6;
        --text: #222;
        --muted: #666;
        --bg: #fafafa;
        --panel: #fff;
    }

    body {
        background-color: #648C8C;
        /* vert de fond */
        color: #222;
        font-family: "Nunito", sans-serif;
    }

    /* Cartes de catégories */
    .card {
        background-color: #B7BDB9;
        /* gris-vert clair */
        border-radius: 15px;
        padding: 15px;
    }

    /* Titres des cartes */
    .card-head {
        font-family: "Playfair Display", serif;
        font-weight: 600;
        font-size: 18px;
        text-align: center;
        margin-bottom: 10px;
    }

    .card {
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 14px;
    }

    .card .thumb {
        width: 100%;
        aspect-ratio: 3/4;
        /* même ratio que la capture */
        border-radius: calc(var(--radius) - 6px);
        border: 2px dashed rgba(0, 0, 0, .12);
        /* liseré pointillé gris clair */
        background: #fff;
        display: grid;
        place-items: center;
        overflow: hidden;
    }

    .card .thumb img {
        width: 100%;
        height: auto;
        object-fit: contain;
        object-position: center;
        display: block;
        aspect-ratio: 3/4;
    }

    .card {
        height: auto;
        width: max-content;
        /* 
  border: 5px solid var(--colorB); */
        border-radius: 130px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        object-fit: contain;
    }

    /* Liste des prestations */
    .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: transparent;
        padding: 8px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .row-title {
        flex: 1;
    }

    .row-price {
        width: 70px;
        text-align: right;
        font-weight: 600;
    }

    /* Boutons */
    .btn {
        border: none;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 14px;
        cursor: pointer;
        font-weight: 500;
    }

    .btn:hover {
        opacity: 0.9;
    }

    /* Bouton "Modifier" */
    .btn[data-action="edit"],
    .btn[data-action="edit-category"] {
        background-color: #7C8E89;
        /* gris légèrement vert */
        color: #fff;
    }

    /* Bouton "Supprimer" */
    .btn[data-action="delete"],
    form button.btn {
        background-color: #b96a6a;
        color: #fff;
    }


    .btn[data-action="delete"]:hover,
    form button.btn:hover {
        background-color: #ca4f4fff;
    }


    /* Bouton "Ajouter" */
    .btn[data-action="add-service"],
    .btn[data-action="add-category"] {
        background-color: #9BAFAA;
        color: #fff;
        font-weight: 600;
    }

    /* Encadré central + */
    .add-box {
        background-color: #A1B3AE;
        border-radius: 20px;
        padding: 25px;
        text-align: center;
    }

    .plus {
        background-color: #C5D1CD;
        color: #fff;
        font-size: 40px;
        border-radius: 10px;
        width: 70px;
        height: 70px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
    }

    /* Boutons bas de page */
    .page-foot .btn {
        border-radius: 10px;
        padding: 10px 25px;
        font-size: 15px;
    }

    .page-foot .btn[href*="Retour"] {
        background-color: #A1B3AE;
        color: #fff;
    }

    .page-foot button[type="submit"] {
        background-color: #C3CDBD;
        color: #000;
        font-weight: 600;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        color: var(--text);
        background: var(--bg);
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    .page {
        max-width: 1100px;
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
        font-size: clamp(18px, 2.4vw, 24px);
        font-weight: 700;
    }

    .whoami {
        font-size: 14px;
        color: var(--muted);
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        justify-items: center;
    }

    @media (max-width: 860px) {
        .grid {
            grid-template-columns: 1fr !important;
        }
    }

    .card {
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    }

    .card>.card-head {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 6px;
    }

    .list {
        border: 1px dashed var(--border);
        border-radius: calc(var(--radius) - 6px);
        padding: 8px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-height: 220px;
    }

    .row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
        align-items: center;
        padding: 8px 10px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #fff;
    }

    .row-title {
        font-size: 15px;
        line-height: 1.3;
    }

    .row-price {
        font-weight: 700;
        white-space: nowrap;
    }

    .row-actions {
        grid-column: 1 / -1;
        display: flex;
        gap: 8px;
        margin-top: 6px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 14px;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: #a2b29f;
        font-size: 14px;
        cursor: pointer;
    }

    .btn:hover {
        background: #8b9b86;
    }

    .btn-ghost {
        background: transparent;
    }

    .btn-wide {
        min-width: 120px;
    }

    .card-foot {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 6px;
    }

    .add-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin: 28px auto;
        width: min(440px, 92%);
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        text-align: center;
    }

    .plus {
        font-size: 40px;
        line-height: 1;
        width: 64px;
        height: 64px;
        display: grid;
        place-items: center;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #fff;
        user-select: none;
    }

    .page-foot {
        display: flex;
        gap: 12px;
        justify-content: space-between;
        margin-top: 20px;
    }

    .page-foot .left,
    .page-foot .right {
        display: flex;
        gap: 12px;
    }

    /* Modal */
    .modal {
        position: fixed;
        inset: 0;
        display: none;
    }

    .modal[aria-hidden="false"] {
        display: block;
    }

    .modal__backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .35);
    }

    .modal__panel {
        position: absolute;
        left: 35%;
        top: 35%;
        /* 
        transform: translate(-50%, -50%); */
        width: min(520px, 92%);
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
        overflow: hidden;
    }

    .modal__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        border-bottom: 1px solid #e6e6e6;
    }

    .modal__head h3 {
        margin: 0;
        font-size: 18px;
    }

    .modal__close {
        border: 0;
        background: transparent;
        font-size: 22px;
        line-height: 1;
        cursor: pointer;
    }

    .modal__body {
        padding: 16px 18px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field input {
        height: 38px;
        padding: 0 10px;
        border: 1px solid #d6d6d6;
        border-radius: 10px;
    }

    .modal__actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 6px;
    }

    .photo-grid {

        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-auto-rows: auto;
        gap: 20px;
        width: 100%;
    }

    body.no-scroll {
        overflow: hidden;
    }
    </style>
</head>

<body class="admin">
    <div class="bandeau_admin">
        <section>Modification galerie-<?= htmlspecialchars($section) ?></section>
        <section>Conecter en tant que : <?= htmlspecialchars($_SESSION['pseudo'], ENT_QUOTES, 'UTF-8') ?>
        </section>
    </div>
    <div class="page">
        <header class="page-head">
        </header>

        <main class="grid" style="gap:22px;">
            <?php while ($cat = $cats->fetch(PDO::FETCH_ASSOC)): ?>
            <?php
          $photosStmt->execute([':cid' => (int)$cat['id']]);
          $photos = $photosStmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <section class="card">
                <div class="card-head" style="justify-content:flex-start">
                    <h3 style="margin:0;font-family:'Playfair Display',serif;font-weight:600;">
                        <?= htmlspecialchars($cat['name']) ?>
                    </h3>
                </div>

                <div class="list" style="border-style:solid;border-width:1px">
                    <div class="photo-grid">
                        <?php foreach ($photos as $p): ?>
                        <article class="tile" data-id="<?= (int)$p['id'] ?>">
                            <div class="thumb">
                                <?php
                      $src = $p['current_url'] ?: $p['pending_url']; // affiche qqch même en brouillon
                      if ($src):
                    ?>
                                <img src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($p['alt'] ?? '') ?>">
                                <?php else: ?>
                                <span>Photo <?= (int)$p['id'] ?></span>
                                <?php endif; ?>
                            </div>


                            <div class="tile-actions">
                                <!-- Modifier = remplacer (ouvre modale upload pour pending_url) -->
                                <button class="btn edit-photo" data-id="<?= (int)$p['id'] ?>"
                                    data-alt="<?= htmlspecialchars($p['alt'] ?? '', ENT_QUOTES) ?>">
                                    Modifier
                                </button>

                                <!-- Supprimer -->
                                <form method="post" action="gallery_photo_delete.php"
                                    onsubmit="return confirm('Supprimer cette photo ?');">
                                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                    <button class="btn btn-delete" type="submit">Supprimer</button>
                                </form>
                            </div>

                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card-foot">
                    <!-- Ajouter une photo dans cette catégorie -->
                    <button class="btn btn-wide add-photo" data-cat-id="<?= (int)$cat['id'] ?>"
                        data-cat-name="<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>">
                        Ajouter
                    </button>
                </div>

                <!-- Actions de la catégorie (déplacement / édition / suppression) -->
                <div class="card-foot">
                    <form method="post" action="gallery_category_move.php" style="display:inline">
                        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                        <input type="hidden" name="dir" value="up">
                        <button class="btn btn-ghost" type="submit" title="Monter">▲</button>
                    </form>

                    <button class="btn edit-gallery-category" data-cat-id="<?= (int)$cat['id'] ?>"
                        data-cat-name="<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>"
                        data-cat-slug="<?= htmlspecialchars($cat['slug'], ENT_QUOTES) ?>">
                        Modifier
                    </button>

                    <form method="post" action="gallery_category_delete.php"
                        onsubmit="return confirm('Supprimer la catégorie et ses photos ?');">
                        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                        <button class="btn btn-delete" type="submit">Supprimer</button>
                    </form>

                    <form method="post" action="gallery_category_move.php" style="display:inline">
                        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                        <input type="hidden" name="dir" value="down">
                        <button class="btn btn-ghost" type="submit" title="Descendre">▼</button>
                    </form>
                </div>
            </section>
            <?php endwhile; ?>
        </main>

        <!-- Bloc “Ajouter une catégorie” -->
        <section class="add-box">
            <button class="btn btn-wide" id="open-modal-gallery-category" type="button">
                Ajouter une catégorie
            </button>
        </section>

        <footer class="page-foot">
            <div class="left">
                <a class="btn"
                    href="../<?= htmlspecialchars($section) ?>/<?= htmlspecialchars($section) ?>.php">Retour</a>
            </div>
            <div class="right">
                <form method="post" action="valider.php">
                    <input type="hidden" name="action" value="gallery_photos">
                    <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
                    <button class="btn" type="submit">Valider</button>
                </form>
            </div>
        </footer>
    </div>

    <!-- MODALE : Ajouter une photo (PENDING) -->
    <div class="modal" id="modal-photo" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="modal-photo-title">
        <div class="modal__backdrop" data-close></div>
        <div class="modal__panel" role="document">
            <header class="modal__head">
                <h3 id="modal-photo-title">Ajouter une photo – <span id="mp-cat-name"></span></h3>
                <button class="modal__close" type="button" data-close aria-label="Fermer">×</button>
            </header>
            <form class="modal__body" id="form-photo" method="post" action="gallery_photo_new.php"
                enctype="multipart/form-data">
                <input type="hidden" name="category_id" id="mp-cat-id">
                <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
                <label class="field">
                    <span>Fichier image</span>
                    <input type="file" name="file" accept="image/*" required>
                </label>
                <label class="field">
                    <span>Texte alternatif (alt)</span>
                    <input type="text" name="alt" placeholder="Décrivez brièvement la photo">
                </label>
                <div class="modal__actions">
                    <button class="btn btn-ghost" type="button" data-close>Annuler</button>
                    <button class="btn btn-edit" type="submit">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODALE : Remplacer une photo (écrit dans PENDING) -->
    <div class="modal" id="modal-photo-edit" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="modal-photo-edit-title">
        <div class="modal__backdrop" data-close></div>
        <div class="modal__panel" role="document">
            <header class="modal__head">
                <h3 id="modal-photo-edit-title">Remplacer la photo</h3>
                <button class="modal__close" type="button" data-close aria-label="Fermer">×</button>
            </header>
            <form class="modal__body" id="form-photo-edit" method="post" action="gallery_photo_replace.php"
                enctype="multipart/form-data">
                <input type="hidden" name="id" id="mpe-id">
                <label class="field">
                    <span>Nouveau fichier image</span>
                    <input type="file" name="file" accept="image/*" required>
                </label>
                <label class="field">
                    <span>Texte alternatif (alt)</span>
                    <input type="text" name="alt" id="mpe-alt" placeholder="Alt de la photo">
                </label>
                <div class="modal__actions">
                    <button class="btn btn-ghost" type="button" data-close>Annuler</button>
                    <button class="btn btn-edit" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODALE : Ajouter une catégorie (galerie) -->
    <div class="modal" id="modal-gcat" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="modal-gcat-title">
        <div class="modal__backdrop" data-close></div>
        <div class="modal__panel" role="document">
            <header class="modal__head">
                <h3 id="modal-gcat-title">Ajouter une catégorie</h3>
                <button class="modal__close" type="button" data-close aria-label="Fermer">×</button>
            </header>
            <form class="modal__body" id="form-gcat" method="post" action="gallery_category_new.php">
                <input type="hidden" name="type" value="<?= htmlspecialchars($section) ?>">
                <label class="field">
                    <span>Nom</span>
                    <input type="text" name="name" required>
                </label>
                <label class="field">
                    <span>Slug</span>
                    <input type="text" name="slug" placeholder="ex: wavy-demi-attaches">
                </label>
                <div class="modal__actions">
                    <button class="btn btn-ghost" type="button" data-close>Annuler</button>
                    <button class="btn btn-edit" type="submit">Créer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODALE : Modifier une catégorie (galerie) -->
    <div class="modal" id="modal-gcat-edit" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="modal-gcat-edit-title">
        <div class="modal__backdrop" data-close></div>
        <div class="modal__panel" role="document">
            <header class="modal__head">
                <h3 id="modal-gcat-edit-title">Modifier la catégorie</h3>
                <button class="modal__close" type="button" data-close aria-label="Fermer">×</button>
            </header>
            <form class="modal__body" id="form-gcat-edit" method="post" action="gallery_category_edit.php">
                <input type="hidden" name="id" id="mgce-id">
                <input type="hidden" name="type" value="<?= htmlspecialchars($section) ?>">
                <label class="field">
                    <span>Nom</span>
                    <input type="text" name="name" id="mgce-name" required>
                </label>
                <label class="field">
                    <span>Slug</span>
                    <input type="text" name="slug" id="mgce-slug" placeholder="ex: chignon">
                </label>
                <div class="modal__actions">
                    <button class="btn btn-ghost" type="button" data-close>Annuler</button>
                    <button class="btn btn-edit" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    (function() {
        const $ = (s, r = document) => r.querySelector(s);
        const $$ = (s, r = document) => [...r.querySelectorAll(s)];

        function openModal(el) {
            el.setAttribute('aria-hidden', 'false');
            document.body.classList.add('no-scroll');
        }

        function closeModal(el) {
            el.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('no-scroll');
        }

        // --- Ajouter photo ---
        const mAdd = $('#modal-photo');
        $$('.add-photo').forEach(btn => {
            btn.addEventListener('click', () => {
                $('#mp-cat-id').value = btn.dataset.catId || '';
                $('#mp-cat-name').textContent = btn.dataset.catName || '';
                // reset
                $('#form-photo').reset();
                openModal(mAdd);
            });
        });

        // --- Remplacer photo ---
        const mEditPhoto = $('#modal-photo-edit');
        $$('.edit-photo').forEach(btn => {
            btn.addEventListener('click', () => {
                $('#mpe-id').value = btn.dataset.id || '';
                $('#mpe-alt').value = btn.dataset.alt || '';
                $('#form-photo-edit').reset(); // reset file
                // Remettre les champs déjà connus
                $('#mpe-id').value = btn.dataset.id || '';
                $('#mpe-alt').value = btn.dataset.alt || '';
                openModal(mEditPhoto);
            });
        });

        // --- Ajouter catégorie galerie ---
        const mGAdd = $('#modal-gcat');
        const openAddCat = $('#open-modal-gallery-category');
        if (openAddCat) openAddCat.addEventListener('click', () => {
            $('#form-gcat').reset();
            openModal(mGAdd);
        });

        // --- Modifier catégorie galerie ---
        const mGEdit = $('#modal-gcat-edit');
        $$('.edit-gallery-category').forEach(btn => {
            btn.addEventListener('click', () => {
                $('#mgce-id').value = btn.dataset.catId || '';
                $('#mgce-name').value = btn.dataset.catName || '';
                $('#mgce-slug').value = btn.dataset.catSlug || '';
                openModal(mGEdit);
            });
        });

        // --- Fermer (backdrop / X / Esc) pour toutes les modales ---
        $$('.modal').forEach(mod => {
            mod.addEventListener('click', (e) => {
                if (e.target.matches('[data-close], .modal__backdrop')) closeModal(mod);
            });
        });
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') $$('.modal[aria-hidden="false"]').forEach(mod => closeModal(mod));
        });
    })();
    </script>
</body>

</html>