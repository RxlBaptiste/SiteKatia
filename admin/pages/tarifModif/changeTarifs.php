<?php
// auth.php
declare(strict_types=1);
session_start();
require __DIR__ . '../../../db.php';
// section = maquillage | coiffure | photographie
$section = $_GET['section'] ?? 'maquillage';
$allowed = ['maquillage','coiffure','photographie'];
if (!in_array($section, $allowed, true)) $section = 'maquillage';

$sectionLabel = [
  'maquillage'     => 'maquillage',
  'coiffure'       => 'coiffure',
  'photographie'   => 'photographie'
][$section];

// Si pas connecté → renvoyer vers la page du formulaire (index.html)
if (!isset($_SESSION['pseudo'])) {
    header('Location: ../../index.php'); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Espace administrateur du site Katia Bulimar. Accès réservé.">

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../../../style.css" />
    <title>Modification tarifs <?= htmlspecialchars($sectionLabel) ?></title>
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
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
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

    body.no-scroll {
        overflow: hidden;
    }

    @media (max-width: 860px) {
        .grid {
            grid-template-columns: 1fr;
        }

        .card {
            width: auto;
        }
    }
    </style>
</head>

<body class="admin">
    <div class="bandeau_admin">
        <section>Modification tarifs <?= htmlspecialchars($sectionLabel) ?></section>
        <section>Conecter en tant que : <?= htmlspecialchars($_SESSION['pseudo'], ENT_QUOTES, 'UTF-8') ?>
        </section>
    </div>
    <div class="page">

        <main class="grid">

            <?php 
             


            $cats = $pdo->prepare(
            "SELECT `id`, `name`, `slug` FROM `categories`
  WHERE type = :t ORDER BY `sort_order`, `id`");
  
            $cats->execute([':t' => $section]);
  while ($cat = $cats->fetch()){

            $servs = $pdo->prepare(
            "SELECT `id`, `label`, `price` FROM `services` WHERE category_id = :cat_id ORDER BY `sort_order`, `id`");
?>
            <section class="card">
                <div class="card-head">
                    Tarifs <?php echo ($cat['slug']) ?>
                </div>
                <div class="list"><?php 
                $servs->execute([':cat_id' => (int)$cat['id']]);
                while ($serv = $servs->fetch()){  ?>
                    <article class="row" data-id="ID_SERVICE">
                        <div class="row-title"> <?php echo ($serv['label']) ?></div>
                        <div class="row-price">
                            <?php 
                            if ((int)$serv['price'] === 0) {
                              echo 'Sur devis';
                            } else {
                              echo htmlspecialchars((string)$serv['price']) . ' €';
                            }
                            ?>
                        </div>
                        <div class="row-actions">
                            <button class="btn edit-service" data-id="<?= (int)$serv['id'] ?>"
                                data-label="<?= htmlspecialchars($serv['label'], ENT_QUOTES, 'UTF-8') ?>"
                                data-price="<?= (int)$serv['price'] ?>">Modifier</button>
                            <form method="post" action="service_delete.php"
                                onsubmit="return confirm('Supprimer ce service ?');">
                                <input type="hidden" name="id" value="<?= (int)$serv['id'] ?>">
                                <button class="btn" type="submit">Supprimer</button>
                            </form>
                        </div>
                    </article>
                    <?php } ?>
                </div>

                <div class="card-foot">
                    <button class="btn btn-wide add-service" data-cat-id="<?= (int)$cat['id'] ?>"
                        data-cat-name="<?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>">
                        Ajouter
                    </button>
                </div>

                <!-- Actions catégorie (optionnelles) -->
                <div class="card-foot">
                    <form method="post" action="category_move.php" style="display:inline">
                        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                        <input type="hidden" name="dir" value="up">
                        <button class="btn" type="submit" title="Monter">▲</button>
                    </form>

                    <button class="btn edit-category" data-cat-id="<?= (int)$cat['id'] ?>"
                        data-cat-name="<?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>"
                        data-cat-slug="<?= htmlspecialchars($cat['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>">Modifier</button>
                    <form method="post" action="category_delete.php"
                        onsubmit="return confirm('Supprimer la catégorie ?');">
                        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                        <button class="btn" type="submit">Supprimer</button>
                    </form>
                    <form method="post" action="category_move.php" style="display:inline">
                        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                        <input type="hidden" name="dir" value="down">
                        <button class="btn" type="submit" title="Descendre">▼</button>
                    </form>
                </div>
            </section>
            <?php }?>
        </main>

        <!-- Bloc “+ Ajouter une catégorie” -->
        <section class="add-box">
            <button class="btn btn-wide" id="open-modal-category" type="button">Ajouter une catégorie</button>
        </section>

        <!-- Footer actions -->
        <footer class="page-foot">
            <!-- 
            <div class="left">
                <a class="btn" href="maquillage.php">Retour</a>
            </div> -->
            <div class="right">
                <a class="btn" type="submit"
                    href="../<?= htmlspecialchars($sectionLabel) ?>/<?= htmlspecialchars($sectionLabel) ?>.php">Valider</a>
            </div>
        </footer>
    </div>

    <!--LES MODALES-->

    <div class="modal" id="modal-service" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="modal-service-title">
        <div class="modal__backdrop" data-modal-close></div>

        <div class="modal__panel" role="document">
            <header class="modal__head">
                <h3 id="modal-service-title">Tarifs <span id="modal-cat-name"></span></h3>
                <button class="modal__close" type="button" data-modal-close aria-label="Fermer">×</button>
            </header>

            <form id="form-service" method="post" action="service_new.php" class="modal__body">
                <input type="hidden" name="category_id" id="modal-category-id">
                <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">

                <label class="field">
                    <span>Titre</span>
                    <input type="text" name="label" id="modal-label" required>
                </label>

                <label class="field">
                    <span>Prix (€)</span>
                    <input type="number" name="price" id="modal-price" min="0" step="1" placeholder="0 = Sur devis">
                </label>

                <footer class="modal__actions">
                    <button class="btn" type="button" data-modal-close>Annuler</button>
                    <button class="btn" type="submit">Valider</button>
                </footer>
            </form>
        </div>
    </div>
    <!-- MODALE : Ajouter une catégorie -->
    <div class="modal" id="modal-category" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="modal-category-title">
        <div class="modal__backdrop" data-modal-close></div>

        <div class="modal__panel" role="document">
            <header class="modal__head">
                <h3 id="modal-category-title">Ajouter une catégorie</h3>
                <button class="modal__close" type="button" data-modal-close aria-label="Fermer">×</button>
            </header>

            <form id="form-category" method="post" action="category_new.php" class="modal__body">
                <input type="hidden" name="type" value="<?= htmlspecialchars($section) ?>">
                <label class="field">
                    <span>Nom de la catégorie</span>
                    <input type="text" name="name" id="category-name" required>
                </label>

                <label class="field">
                    <span>Slug</span>
                    <input type="text" name="slug" id="category-slug" placeholder="ex: maquillage">
                </label>

                <footer class="modal__actions">
                    <button class="btn" type="button" data-modal-close>Annuler</button>
                    <button class="btn" type="submit">Valider</button>
                </footer>
            </form>
        </div>
    </div>
    <!-- MODALE : Modifier un service -->
    <div class="modal" id="modal-edit-service" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="modal-edit-title">
        <div class="modal__backdrop" data-modal-close></div>

        <div class="modal__panel" role="document">
            <header class="modal__head">
                <h3 id="modal-edit-title">Modifier le service</h3>
                <button class="modal__close" type="button" data-modal-close aria-label="Fermer">×</button>
            </header>

            <form id="form-edit-service" method="post" action="service_edit.php" class="modal__body">
                <input type="hidden" name="id" id="edit-id">

                <label class="field">
                    <span>Titre</span>
                    <input type="text" name="label" id="edit-label" required>
                </label>

                <label class="field">
                    <span>Prix (€)</span>
                    <input type="number" name="price" id="edit-price" min="0" step="1" placeholder="0 = Sur devis">
                </label>

                <footer class="modal__actions">
                    <button class="btn" type="button" data-modal-close>Annuler</button>
                    <button class="btn" type="submit">Enregistrer</button>
                </footer>
            </form>
        </div>
    </div>
    <!-- MODALE : Modifier une catégorie -->
    <div class="modal" id="modal-edit-category" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="modal-edit-cat-title">
        <div class="modal__backdrop" data-modal-close></div>

        <div class="modal__panel" role="document">
            <header class="modal__head">
                <h3 id="modal-edit-cat-title">Modifier la catégorie</h3>
                <button class="modal__close" type="button" data-modal-close aria-label="Fermer">×</button>
            </header>

            <form id="form-edit-category" method="post" action="category_edit.php" class="modal__body">
                <input type="hidden" name="type" value="<?= htmlspecialchars($section) ?>">
                <input type="hidden" name="id" id="edit-cat-id">

                <label class="field">
                    <span>Nom</span>
                    <input type="text" name="name" id="edit-cat-name" required>
                </label>

                <label class="field">
                    <span>Slug</span>
                    <input type="text" name="slug" id="edit-cat-slug" placeholder="ex: mariage">
                </label>

                <footer class="modal__actions">
                    <button class="btn" type="button" data-modal-close>Annuler</button>
                    <button class="btn" type="submit">Enregistrer</button>
                </footer>
            </form>
        </div>
    </div>
</body>
<script>
//BTN AJOUTER UN SERVICE//
(function() {
    const modal = document.getElementById('modal-service');
    const catSpan = document.getElementById('modal-cat-name');
    const catInput = document.getElementById('modal-category-id');
    const labelInput = document.getElementById('modal-label');
    const priceInput = document.getElementById('modal-price');

    function openModal(catId, catName) {
        catInput.value = catId;
        catSpan.textContent = catName || '';
        labelInput.value = '';
        priceInput.value = '';
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('no-scroll');
        setTimeout(() => labelInput.focus(), 0);
    }

    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('no-scroll');
    }

    // Ouvrir depuis chaque bouton "Ajouter"
    document.querySelectorAll('.add-service').forEach(btn => {
        btn.addEventListener('click', () => {
            openModal(btn.dataset.catId, btn.dataset.catName);
        });
    });

    // Fermer via backdrop / bouton / ESC
    modal.addEventListener('click', (e) => {
        if (e.target.matches('[data-modal-close], .modal__backdrop')) closeModal();
    });
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') closeModal();
    });
})();
</script>
<script>
//BTN AJOUTER UNE CATEGORIE//
(function() {
    const modal = document.getElementById('modal-category');
    const openBtn = document.getElementById('open-modal-category');
    const nameInput = document.getElementById('category-name');
    const slugInput = document.getElementById('category-slug');

    function openModal() {
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('no-scroll');
        nameInput.value = '';
        slugInput.value = '';
        setTimeout(() => nameInput.focus(), 100);
    }

    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('no-scroll');
    }

    openBtn.addEventListener('click', openModal);
    modal.addEventListener('click', (e) => {
        if (e.target.matches('[data-modal-close], .modal__backdrop')) closeModal();
    });
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') closeModal();
    });
})();
</script>
<script>
(function() {
    const modal = document.getElementById('modal-edit-service');
    const idI = document.getElementById('edit-id');
    const labelI = document.getElementById('edit-label');
    const priceI = document.getElementById('edit-price');

    function openModal(id, label, price) {
        idI.value = id;
        labelI.value = label || '';
        priceI.value = (price !== undefined && price !== null) ? price : 0;
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('no-scroll');
        setTimeout(() => labelI.focus(), 0);
    }

    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('no-scroll');
    }

    // Ouvrir depuis chaque bouton "Modifier"
    document.querySelectorAll('.edit-service').forEach(btn => {
        btn.addEventListener('click', () => {
            openModal(btn.dataset.id, btn.dataset.label, btn.dataset.price);
        });
    });

    // Fermer via backdrop / bouton / ESC
    modal.addEventListener('click', (e) => {
        if (e.target.matches('[data-modal-close], .modal__backdrop')) closeModal();
    });
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') closeModal();
    });
})();
</script>
<script>
(function() {
    const modal = document.getElementById('modal-edit-category');
    const idI = document.getElementById('edit-cat-id');
    const nameI = document.getElementById('edit-cat-name');
    const slugI = document.getElementById('edit-cat-slug');

    function openModal(id, name, slug) {
        idI.value = id;
        nameI.value = name || '';
        slugI.value = slug || '';
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('no-scroll');
        setTimeout(() => nameI.focus(), 0);
    }

    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('no-scroll');
    }

    // Ouvrir depuis chaque bouton "Modifier" de catégorie
    document.querySelectorAll('.edit-category').forEach(btn => {
        btn.addEventListener('click', () => {
            openModal(btn.dataset.catId, btn.dataset.catName, btn.dataset.catSlug);
        });
    });

    // Fermer via backdrop / bouton / ESC
    modal.addEventListener('click', (e) => {
        if (e.target.matches('[data-modal-close], .modal__backdrop')) closeModal();
    });
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') closeModal();
    });
})();
</script>

</html>