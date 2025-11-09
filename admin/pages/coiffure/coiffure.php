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
    <title>Coiffure – Katia Bulimar</title>
    <link href="https://fonts.googleapis.com/css2?family=Castoro+Titling&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../../style.css" />
    <!-- Polices -->
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
            <a class="lienNav" href="../../modifier/index.php">ACCUEIL</a>
            <a class="lienNav" href="../maquillage/maquillage.php">MAQUILLAGE</a>
            <a class="lienNav active" href="../coiffure/coiffure.php">COIFFURE</a>
            <a class="lienNav" href="../photographie/photographie.php">PHOTOGRAPHIE</a>
            <a class="lienNav" href="../contact.php">CONTACT</a>

            <button class="btn_nav_bd"><a class="nav-logout" aria-label="Se déconnecter"
                    href="../../logout.php">Déconnexion</a></button>
        </nav>
    </header>

    <!-- Titre page -->
    <!-- Hero -->
    <section class="hero container castoro-titling-regular">
        <section class="title-accueil autrepage">
            <h1>KATIA BULIMAR</h1>
            <div class="subtitle lato-regular">
                MAQUILLAGE
                <span class="dot-sub">|</span>
                COIFFURE
                <span class="dot-sub">|</span>
                PHOTOGRAPHIE
            </div>
        </section>
        <hr class="hr">

        <main class="container-title">
            <section class="hero-page castoro-titling-regular">
                <h1 class="page-title">COIFFURE</h1>
            </section>

            <!-- Intro / description -->
            <section class="intro">
                <span class="lato-regular-italic subtitle_preintro">Parce qu’une coiffure réussie complète parfaitement
                    votre mise en beauté, je vous accompagne pour créer une harmonie entre votre visage, votre tenue et
                    votre personnalité.
                </span>
            </section>

            <!-- Tarifs -->
            <section class="container_admin"><a href="../tarifModif/changeTarifs.php?section=coiffure"
                    class="btn_bd_inv">Modifier</a>
            </section>
            <section class="tarifs">
                <div class="tarifs-grid">
                    <!-- Colonne 1 -->

                    <?php 
             


            $cats = $pdo->query(
            "SELECT `id`, `name`, `slug` FROM `categories` WHERE type = 'coiffure' ORDER BY `sort_order`, `id`"); while
            ($cat = $cats->fetch()){

            $servs = $pdo->prepare(
            "SELECT `id`, `label`, `price` FROM `services` WHERE category_id = :cat_id ORDER BY `sort_order`, `id`");
?>
                    <article class="tarif-col">
                        <h3 class="lato-regular"> <?php echo ($cat['slug']) ?></h3>
                        <ul><?php 
                $servs->execute([':cat_id' => (int)$cat['id']]);
                while ($serv = $servs->fetch()){  ?>
                            <li><?php echo ($serv['label']) ?> <span class="price">
                                    <?php 
                            if ((int)$serv['price'] === 0) {
                              echo 'Sur devis';
                            } else {
                              echo htmlspecialchars($serv['price']) . ' €';
                            }
                            ?></span></li>
                            <?php } ?>
                        </ul>
                    </article>

                    <?php } ?>
                </div>
            </section>

            <!-- Bouton -->
            <p class="container-btn">
                <a class="btn castoro-regular" href="galeries.php">Voir les coiffures</a>
            </p>
        </main>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container-logo">
            <img class="logo" src="../../../assets/logo3.png" alt="Logo Katia Bulimar" />
        </div>

        <div class="container-text">
            <div class="footer-links castoro-titling-regular">
                <a class="lien-footer" href="../../modifier/index.php">ACCUEIL</a> |
                <a class="lien-footer" href="../coiffure/coiffure.php">COIFFURE</a>
                |
                <a class="lien-footer" href="../maquillage/maquillage.php">MAQUILLAGE</a>
                |
                <a class="lien-footer" href="../photographie/photographie.php">PHOTOGRAPHIE</a>
                |
                <a class="lien-footer" href="../contact.php">CONTACT</a>
            </div>

            <span class="lato-regular-italic colorB">
                Katia Bulimar - Maquilleuse professionnelle
            </span>

            <span class="mentions">
                ©<span id="year"></span> Katia Bulimar — Tous droits réservés |
                <a class="mention-Rgpd" href="../RGPD.php">Mentions légales - RGPD</a>
            </span>
        </div>

        <div class="container-socials">
            <div class="socials" aria-label="Réseaux sociaux">
                <a href="#" aria-label="Instagram">
                    <img class="icon insta" src="../../../assets/icons8-instagram-96.png" alt="Instagram" />
                </a>
                <a href="#" aria-label="Facebook">
                    <img class="icon f" src="../../../assets/icons8-facebook-96.png" alt="Facebook" />
                </a>
            </div>
        </div>
    </footer>

    <script src="../../../script.js"></script>
</body>

</html>