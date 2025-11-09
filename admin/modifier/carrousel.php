<?php
// auth.php
declare(strict_types=1);
session_start();
require __DIR__ . '/../db.php';

// Si pas connecté → renvoyer vers la page du formulaire (index.html)
if (!isset($_SESSION['pseudo'])) {
    header('Location: ../index.php'); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
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
        <h1>Modification carrousel</h1>
        <small>Connecter en tant que : <strong>ADMIN</strong></small>
    </header>

    <main class="wrapper">

        <section class="cards" aria-label="Gestion du carrousel">
            <!-- Photo 1 -->
            <?php 
              $sql = $pdo->query("SELECT * FROM carousel ORDER BY id  ");
              ($images = $sql->fetchAll()); 
              foreach ($images as $img): ?>
            <article class="card">
                <div aria-label="Aperçu photo 1">
                    <span>

                        <img class="thumb"
                            src="<?= htmlspecialchars($img['pending_image_url']?: $img['curent_image_url']) ?>"
                            alt="Image">
                    </span>
                </div>
                <div class="actions">
                    <button class="btn btn-edit" type="button" data-id="<?= $img['id'] ?>">Modifier
                        &nbsp<?= $img['id']; ?></button>
                </div>
            </article>
            <?php endforeach; ?>
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
                <input type="hidden" name="action" value="carrousel">
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
            <h2 id="upload-title">Ajouter / Remplacer une photo</h2>
            <p id="upload-desc" class="modal__hint">
                Glisser-déposer un fichier ci-dessous ou cliquez sur le bouton.
            </p>

            <form class="dropzone" id="dropzone" action="../images.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="image-id">
                <div class="dropzone__area">
                    <img src="../../assets/admin/drag&drop.svg" alt="Icône de téléchargement" width="50" height="50" />
                    <div class="dz-text">
                        <strong>Glisser-déposer</strong><span>ou</span>
                        <label class="btn btn-edit">
                            Parcourir le fichier
                            <input type="file" name="image" accept="image/png,image/jpeg,image/webp" required
                                id="file-input" hidden />
                        </label>
                    </div>
                </div>
                <p class="hint">JPG ou PNG • max 10 Mo • compression auto</p>

                <div class="modal__actions">
                    <button class="btn btn-light" data-close-modal>Annuler</button>
                    <button type="submit" class="btn btn-edit" id="btn-validate-upload">Valider</button>
                </div>
            </form>
            <!-- Liste des images enregistrées -->
            <div class="grid">
                <?php
      $rows = $pdo->query("SELECT * FROM carousel ORDER BY id
        DESC")->fetchAll();?>
            </div>
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
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];

    const overlay = $("[data-modal-overlay]");
    const modals = {
        upload: $("#modal-upload"),
        confirm: $("#modal-confirm"),
    };

    // Attach ONLY to the edit buttons of the cards (not the modal)
    document.querySelectorAll('.cards .btn-edit[data-id]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const hid = document.getElementById('image-id');
            if (hid) hid.value = btn.dataset.id || '0';
            openModal('upload', btn);
        });
    });

    // If you have delete buttons on the cards:
    document.querySelectorAll('.cards .btn-delete[data-id]').forEach((btn) => {
        btn.addEventListener('click', () => openModal('confirm', btn));
    });

    // ---------- modal infra ----------
    let lastFocused = null;

    function openModal(name, opener) {
        lastFocused = opener || document.activeElement;
        overlay.hidden = false;
        modals[name].hidden = false;

        const focusable = getFocusable(modals[name]);
        if (focusable[0]) focusable[0].focus();

        document.addEventListener("keydown", onKey);
        overlay.addEventListener("click", closeAll, {
            once: true
        });
    }

    function closeAll() {
        overlay.hidden = true;
        Object.values(modals).forEach(m => m.hidden = true);
        document.removeEventListener("keydown", onKey);
        if (lastFocused) lastFocused.focus();
    }

    function onKey(e) {
        if (e.key === "Escape") return closeAll();
        if (e.key === "Tab") {
            const activeModal = Object.values(modals).find(m => !m.hidden);
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
        return $$('button,[href],input,select,textarea,[tabindex]:not([tabindex="-1"])', root)
            .filter(el => !el.hasAttribute("disabled"));
    }

    $$("[data-close-modal]").forEach(b => b.addEventListener("click", closeAll));

    // ---------- dropzone (guarded) ----------
    const dz = $("#dropzone");
    if (dz) {
        const dzArea = $(".dropzone__area", dz);
        const fileInput = $("#file-input");

        ["dragenter", "dragover"].forEach(evt => {
            dzArea.addEventListener(evt, e => {
                e.preventDefault();
                e.dataTransfer.dropEffect = "copy";
                dzArea.classList.add("is-drag");
            });
        });
        ["dragleave", "drop"].forEach(evt => {
            dzArea.addEventListener(evt, e => {
                e.preventDefault();
                dzArea.classList.remove("is-drag");
            });
        });
        dzArea.addEventListener("drop", e => {
            const files = e.dataTransfer.files;
            if (files && files[0]) fileInput.files = files;
        });

        $("#btn-validate-upload")?.addEventListener("click", () => {
            const file = fileInput.files?. [0];
            if (!file) {
                alert("Sélectionne une image.");
                return;
            }
            // envoi fetch/FormData éventuel…
            closeAll();
        });
    }

    $("#btn-confirm-delete")?.addEventListener("click", () => {
        // suppression -> fetch, puis refresh
        closeAll();
    });
    </script>
</body>

</html>