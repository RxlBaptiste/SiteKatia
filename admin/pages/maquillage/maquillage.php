<?php
// auth.php
declare(strict_types=1);
session_start();
require __DIR__ . '/../../db.php';


ini_set('display_errors', 1);
error_reporting(E_ALL);

$baseName = $pdo->query("SELECT DATABASE()")->fetchColumn();
echo "<!-- DEBUG BASE: " . htmlspecialchars($baseName) . " -->";

$nbCatsMaquillage = $pdo
    ->query("SELECT COUNT(*) FROM categories WHERE type = 'maquillage'")
    ->fetchColumn();
echo "<!-- DEBUG CATEGORIES MAQUILLAGE: " . (int)$nbCatsMaquillage . " -->";





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
    <title>Maquillage – Katia Bulimar</title>
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
            <a class="lienNav active" href="../maquillage/maquillage.php">MAQUILLAGE</a>
            <a class="lienNav" href="../coiffure/coiffure.php">COIFFURE</a>
            <a class="lienNav" href="../photographie/photographie.php">PHOTOGRAPHIE</a>
            <a class="lienNav" href="../contact.php">CONTACT</a>

            <button class="btn_nav_bd"><a class="nav-logout" aria-label="Se déconnecter"
                    href="../../logout.php">Déconnexion</a></button>
        </nav>
    </header>

    <!-- Titre page -->
    <!-- Hero -->
    <main class="container-title">
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
            <section class="hero-page castoro-titling-regular">
                <h1 class="page-title">MAQUILLAGE</h1>
            </section>

            <!-- Intro -->
            <section class="intro">
                <span class="lato-regular-italic subtitle_preintro">
                    L'art de sublimer sans transformer
                </span>
            </section>

            <!-- Tarifs -->
            <section class="container_admin"><a href="../tarifModif/changeTarifs.php?section=maquillage"
                    class="btn_bd_inv">Modifier</a>
            </section>
            <section class="tarifs">
                <!-- 
        <h2 class="title_white section-title lato-regular">MES TARIFS</h2>
 -->
                <div class="tarifs-grid">
                    <!-- Colonne 1 -->

                    <?php 
             


            $cats = $pdo->query(
            "SELECT `id`, `name`, `slug` FROM `categories` WHERE type = 'maquillage' ORDER BY `sort_order`, `id`"); while
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
                              echo htmlspecialchars((string)$serv['price']) . ' €';
                            }
                            ?></span></li>
                            <?php } ?>
                        </ul>
                    </article>

                    <?php } ?>
                </div>
            </section>

            <!-- bloc comparatif -->
            <section class="comparatif">
                <h2 class="section-title castoro-titling-regular">
                    MAQUILLAGE NATUREL OU SOPHISTIQUÉ ?
                </h2>

                <article class="bloc-comparatif">
                    <h4 class="lato-regular">Naturel</h3>
                        <section class="container_admin"><a href="maquillage_exemple.php"
                                class="btn_bd_inv">Modifier</a>
                        </section>
                        <div class="bloc-texte">
                            <p class="txtcomparatif lato-regular">
                                C’est généralement pour ce type de maquillage que les femmes me contactent : mariages,
                                soirées, cours d’auto-maquillage ou simplement pour prendre soin d’elles. C’est ma
                                spécialité : très attachée aux détails, tout est pensé sur mesure, pour vous.
                                Il s’agit d’un maquillage léger, qui sublime le teint sans le transformer. Il met en
                                valeur les traits en douceur, avec des tons neutres et discrets. Idéal pour un rendu
                                frais, lumineux et intemporel. Parfait pour celles qui souhaitent rester proches de leur
                                apparence naturelle.
                            </p><?php
$stmt = $pdo->prepare("
    SELECT name, image_path
    FROM maquillage_examples
    WHERE name IN ('naturel', 'sophistique')
");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$imgNaturel     = $rows['naturel']     ?? 'images/maquillage-naturel-default.jpg';
$imgSophistique = $rows['sophistique'] ?? 'images/maquillage-sophistique-default.jpg';
?>
                            <img src="<?= htmlspecialchars($imgNaturel, ENT_QUOTES, 'UTF-8') ?>"
                                alt="Exemple de maquillage naturel" class="bloc-visuel"></img>
                        </div>
                </article>

                <article class="bloc-comparatif inverse">
                    <h4 class="lato-regular">Sophistiqué</h3>
                        <div class="bloc-texte">
                            <img src="<?= htmlspecialchars($imgSophistique, ENT_QUOTES, 'UTF-8') ?>"
                                alt="Exemple de maquillage sophistiqué" class="bloc-visuel"></img>
                            <p class="txtcomparatif lato-regular">
                                C'est un maquillage plus travillé, plus structuré. Il peut inclure un teint plus
                                couvrant, des yeux plus définis (eyesliner, smoky, faux cils, lèvres plus marquées...) 
                                Il reste élégant, mais avec un effet plus "glam" ou affirmé.
                            </p>
                        </div>
                </article>
                <!-- Bouton -->
                <p class="container-btn">
                    <a class="btn lato-regular" href="galerie.php">Voir les maquillages</a>
                </p>
            </section>

            <!-- Vidéo -->
            <section class="video-wrap">
                <div class="video" aria-label="Présentation en vidéo">
                    <iframe class="VideoYT" width="1060" height="490" src="https://www.youtube.com/embed/QuUlXXPWSxQ"
                        title="Présentation maquillage"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
                </div>
            </section>
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

            <span class="lato-regular-italic colorB">Katia Bulimar - Maquilleuse professionnelle</span>

            <span class="mentions">
                ©<span id="year"></span> Katia Bulimar — Tous droits réservés |
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
</body>

</html>