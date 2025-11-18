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
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Espace administrateur du site Katia Bulimar. Accès réservé.">

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, viewport-fit=cover" />
    <title>Galerie – Coiffure | Katia Bulimar</title>
    <link href="https://fonts.googleapis.com/css2?family=Castoro+Titling&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../style.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Nunito:wght@300;400;600&display=swap"
        rel="stylesheet" />
</head>

<body class="<?= isset($_SESSION['pseudo']) ? 'admin' : '' ?>">
    <!-- Header -->
    <header>
        <!-- Bouton burger -->
        <button class="burger" aria-expanded="false" aria-controls="mainnav">
            <span></span><span></span><span></span>
        </button>
        <div class="bandeau_admin">
            <section><button class="btn_nav_bd "><a class="logout-btn" aria-label="Se déconnecter"
                        href="../../logout.php">Déconnexion</a></button></section>
            <section>Conecter en tant que : <?= htmlspecialchars($_SESSION['pseudo'], ENT_QUOTES, 'UTF-8') ?></section>
        </div>
        <nav id="mainnav" class="nav container castoro-titling-regular" aria-label="Navigation principale">
            <a class="lienNav" href="../../index.php">ACCUEIL</a>
            <a class="lienNav" href="../maquillage/maquillage.php">MAQUILLAGE</a>
            <a class="lienNav active" href="./coiffure.php">COIFFURE</a>
            <a class="lienNav" href="../photographie/photographie.php">PHOTOGRAPHIE</a>
            <a class="lienNav" href="../contact.php">CONTACT</a>

            <button class="btn_nav_bd"><a class="nav-logout" aria-label="Se déconnecter"
                    href="../../logout.php">Déconnexion</a></button>
        </nav>
    </header>

    <main class="container-title">
        <section class="hero-page castoro-titling-regular">
            <h1 class="page-title">GALERIE – COIFFURE</h1>
        </section>

        <!-- bouton admin (hors boucle pour éviter les doublons) -->
        <section class="container_admin">
            <a href="../galerieModif/changeGalerie.php?section=coiffure" class="btn_bd_inv">Modifier</a>
        </section>

        <?php
  // 1) Charger les catégories "coiffure"
  $cats = $pdo->query("
      SELECT id, name, slug
      FROM gallery_categories
      WHERE type = 'coiffure'
      ORDER BY sort_order, id
  ");

  // 2) Préparer la requête des photos d'une catégorie
  $photosStmt = $pdo->prepare("
      SELECT id, current_url, alt
      FROM gallery_photos
      WHERE category_id = :cat_id
        AND visible = 1
        AND current_url IS NOT NULL
      ORDER BY sort_order, id
  ");

  while ($cat = $cats->fetch(PDO::FETCH_ASSOC)):
      // exécuter pour la catégorie courante
      $photosStmt->execute([':cat_id' => (int)$cat['id']]);
      $photos = $photosStmt->fetchAll(PDO::FETCH_ASSOC);
  ?>
        <section class="gallery-group">
            <h2 class="section-title castoro-titling-regular">
                <?= htmlspecialchars($cat['slug']) ?>
            </h2>

            <div class="gallery-grid">
                <?php if (!$photos): ?>
                <p>Aucune photo publiée pour cette catégorie.</p>
                <?php else: ?>
                <?php foreach ($photos as $p): ?>
                <figure class="gallery-item">
                    <img class="sr-only" src="<?= htmlspecialchars($p['current_url']) ?>"
                        alt="<?= htmlspecialchars($p['alt'] ?? '') ?>" loading="lazy">
                </figure>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <?php endwhile; ?>
    </main>
    <!-- Lightbox (affiche la photo cliquée en grand) -->
    <div id="lightbox" class="lightbox">
        <span class="close">&times;</span>
        <img id="lightbox-img" src="" alt="Photo en grand" />
    </div>

    <!-- Footer -->
    <footer>
        <div class="container-logo">
            <img class="logo" src="../../../assets/logo3.png" alt="Logo Katia Bulimar" />
        </div>

        <div class="container-text">
            <div class="footer-links castoro-titling-regular">
                <a class="lien-footer" href="../../modifier/index.php">ACCUEIL</a> |
                <a class="lien-footer" href="./coiffure.php">COIFFURE</a> |
                <a class="lien-footer" href="../maquillage/maquillage.php">MAQUILLAGE</a>
                |
                <a class="lien-footer" href="../photographie/photographie.php">PHOTOGRAPHIE</a>
                |
                <a class="lien-footer" href="../contact.php">CONTACT</a>
            </div>

            <span class="lato-regular-italic colorB">Katia Bulimar - Maquilleuse professionnelle</span>

            <span class="mentions">
                © <span id="year"></span> Katia Bulimar — Tous droits réservés |
                <a class="mention-Rgpd" href="../RGPD.php">Mentions légales - RGPD</a>
            </span>
        </div>

        <div class="container-socials">
            <div class="socials" aria-label="Réseaux sociaux">
                <a href="https://www.instagram.com/katia.bulimar?igsh=bmoxYzF1bDNlb3F5&utm_source=qr"
                    aria-label="Instagram">
                    <img class="icon insta" src="../../../assets/icons8-instagram-96.png" alt="Instagram" />
                </a>
                <a href="https://www.facebook.com/share/1Fx32vq1Sn/?mibextid=wwXIfr" aria-label="Facebook">
                    <img class="icon f" src="../../../assets/icons8-facebook-96.png" alt="Facebook" />
                </a>
            </div>
        </div>
    </footer>

    <script src="../../../script.js"></script>
    <script>
    const lightbox = document.getElementById("lightbox");
    const lightboxImg = document.getElementById("lightbox-img");
    const closeBtn = document.querySelector(".lightbox .close");

    // On cible toutes les images à l'intérieur des galeries
    const galleryImages = document.querySelectorAll(
        ".gallery-grid img, .sr-only img"
    );

    galleryImages.forEach((img) => {
        img.addEventListener("click", () => {
            lightboxImg.src = img.src; // Affiche exactement cette image
            lightbox.style.display = "flex"; // Montre la lightbox
        });
    });

    // Ferme la lightbox en cliquant sur la croix
    closeBtn.addEventListener("click", () => {
        lightbox.style.display = "none";
    });

    // Ferme aussi si on clique sur le fond noir
    lightbox.addEventListener("click", (e) => {
        if (e.target === lightbox) {
            lightbox.style.display = "none";
        }
    });
    </script>
</body>

</html>