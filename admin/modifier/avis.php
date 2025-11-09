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
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="robots" content="noindex, nofollow">

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, viewport-fit=cover" />
    <title>Katia Bulimar – Maquillage • Coiffure • Photographie</title>
    <link href="https://fonts.googleapis.com/css2?family=Castoro+Titling&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <!-- Polices -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Nunito:wght@300;400;600&display=swap"
        rel="stylesheet" />
</head>

<body>
    <header class="topbar">
        <h1>Modification avis</h1>
        <small>Connecter en tant que : <strong>ADMIN</strong></small>
    </header>

    <main class="wrapper">

        <section class="cards" aria-label="Gestion du carrousel">
            <!-- Photo 1 -->
            <?php 
              $sql = $pdo->query(" SELECT id, stars, pending_avis, curent_avis FROM avis ORDER BY id ASC");
            while($a = $sql->fetch()) {
                $stars = (int)$a['stars'];
            ?>

            <article class="card">
                <div class="stars">
                    <?php 
                    for ($i = 1; $i <= 5; $i++) echo $i <= $stars ? '★' : '☆'; // étoile pleine ou vide
                ?>
                </div>
                <div aria-label="Aperçu photo 1">
                    <span>
                        <p><?= htmlspecialchars($a['pending_avis']?: $a['curent_avis']) ?></p>
                    </span>
                </div>
                <div class="actions">
                    <button class="btn btn-edit" type="button" data-id="<?= (int)$a['id'] ?>" data-stars="<?= $stars ?>"
                        data-text="<?= htmlspecialchars($a['curent_avis'], ENT_QUOTES, 'UTF-8') ?>">Modifier</button>
                </div>
            </article>
            <?php } ?>
        </section>
        <!-- Carte Ajouter -->
        <!-- 
        <article class="card">
            <div class="add">
                <div class="plus" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>
                <div class="meta">
                    <button class="btn btn-edit" type="button">Ajouter</button>
                    <span class="hint">JPG ou PNG — max 10 Mo · compression auto</span>
                </div>
            </div>
        </article> -->

        <footer class="footer">
            <button class="cta cta-back" type="button"><a href="index.php">Retour</a></button>
            <form method="post" action="valider.php">
                <input type="hidden" name="action" value="avis">
                <button class="cta cta-validate" type="submit">Valider</button>
            </form>
        </footer>
    </main>
    <!-- ===== Modales ===== -->
    <div class="modal-overlay" data-modal-overlay hidden></div>

    <!-- Modale : Upload / Modifier -->
    <div class="modal" id="modal-upload" role="dialog" aria-modal="true" aria-labelledby="upload-title"
        aria-describedby="upload-desc" hidden>
        <div class="modal__panel">
            <h2 id="upload-title">Modifier l'avis</h2>

            <form id="form-avis" action="updateToPending.php" method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" id="avis-id">
                <input type="hidden" name="action" value="update_avis_pending">

                <label class="field">
                    <span>Nombre d’étoiles :</span>
                    <select name="stars" id="avis-stars">
                        <option value="1">★☆☆☆☆ (1)</option>
                        <option value="2">★★☆☆☆ (2)</option>
                        <option value="3">★★★☆☆ (3)</option>
                        <option value="4">★★★★☆ (4)</option>
                        <option value="5">★★★★★ (5)</option>
                    </select>
                </label>
                <br>
                <label class="field">
                    <textarea name="pending_avis" id="avis-text" rows="7" maxlength="1500" required>
                    </textarea>
                    <small>Max 1500 caractères</small>
                </label>

                <div class="modal__actions">
                    <button class="btn btn-light" data-close-modal type="button">Annuler</button>
                    <button type="submit" class="btn btn-submit" id="btn-validate-upload">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modale : Confirmation suppression -->
    <div class="modal" id="modal-confirm" role="dialog" aria-modal="true" aria-labelledby="confirm-title" hidden>
        <div class="modal__panel">
            <h2 id="confirm-title">
                Êtes-vous sûr de supprimer cette image&nbsp;?
            </h2>
            <div class="modal__actions">
                <button class="btn btn-light" data-close-modal>Annuler</button>
                <button class="btn btn-delete" id="btn-confirm-delete">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container-logo">
            <img class="logo" src="../../assets/logo3.png" />
        </div>
        <div class="container-text">
            <div class="footer-links castoro-titling-regular">
                <a class="lien-footer" href="../index.html">ACCUEIL</a> |
                <a class="lien-footer" href="../pages/coiffures/coiffure.html">COIFFURE</a>
                |
                <a class="lien-footer" href="../pages/maquillage/maquillage.html">MAQUILLAGE</a>
                |
                <a class="lien-footer" href="../../pages/photographie/photographie.html">PHOTOGRAPHIE</a>
                |
                <a class="lien-footer" href="../pages/contact.html">CONTACT</a>
            </div>
            <span class="lora-italic">Katia Bulimar - Maquilleuse professionnelle</span>
            <span class="mentions">
                ©<span id="year"></span> Katia Bulimar — Tous droits réservés |
                <a class="mention-Rgpd" href="../pages/RGPD.html">Mentions légales - RGPD</a>
            </span>
        </div>
        <div class="container-socials">
            <div class="socials" aria-label="Réseaux sociaux">
                <a href="#" aria-label="Instagram">
                    <!-- Instagram (simple) -->
                    <img class="icon insta" src="../../assets/icons8-instagram-96.png" />
                </a>
                <a href="#" aria-label="Facebook">
                    <!-- Facebook (simple) -->
                    <img class="icon f" src="../../assets/icons8-facebook-96.png" />
                </a>
            </div>
        </div>
    </footer>
    <script>
    // Ouvrir modales à partir des boutons Modifier / Supprimer
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const overlay = $("[data-modal-overlay]");
    const modals = {
        upload: $("#modal-upload"),
        confirm: $("#modal-confirm"),
    };

    const inputId = $("#avis-id");
    const selectStars = $("#avis-stars");
    const textarea = $("#avis-text");
    // Cibler les boutons existants (met ta grille dans le DOM avant ce script)
    $$(".btn-edit").forEach((btn) => {
        btn.addEventListener("click", () => openModal("upload", btn));
    });
    $$(".btn-delete").forEach((btn) => {
        btn.addEventListener("click", () => openModal("confirm", btn));
    });

    // Ouverture / fermeture + focus trap
    let lastFocused = null;

    function openModal(name, opener) {
        lastFocused = opener || document.activeElement;

        if (name === "upload" && opener) {
            // On lit les data-* placés sur le bouton .btn-edit
            const {
                id,
                stars,
                text
            } = opener.dataset;

            // Injecter dans le formulaire
            inputId.value = id || "";
            selectStars.value = stars || "5";
            textarea.value = text || "";
        }

        overlay.hidden = false;
        modals[name].hidden = false;
        // focus premier bouton/action
        const focusable = getFocusable(modals[name]);
        if (focusable[0]) focusable[0].focus();
        document.addEventListener("keydown", onKey);
        overlay.addEventListener("click", closeAll, {
            once: true
        });
    }

    function closeAll() {
        overlay.hidden = true;
        Object.values(modals).forEach((m) => (m.hidden = true));
        document.removeEventListener("keydown", onKey);
        if (lastFocused) lastFocused.focus();
    }

    function onKey(e) {
        if (e.key === "Escape") return closeAll();
        if (e.key === "Tab") {
            const activeModal = Object.values(modals).find((m) => !m.hidden);
            if (!activeModal) return;
            const focusables = getFocusable(activeModal);
            if (!focusables.length) return;
            const first = focusables[0],
                last = focusables[focusables.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    }

    function getFocusable(root) {
        return $$(
            'button,[href],input,select,textarea,[tabindex]:not([tabindex="-1"])',
            root
        ).filter((el) => !el.hasAttribute("disabled"));
    }
    $$("[data-close-modal]").forEach((b) =>
        b.addEventListener("click", closeAll)
    );
    </script>
</body>

</html>