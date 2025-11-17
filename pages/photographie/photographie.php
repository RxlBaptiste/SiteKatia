<?php
require __DIR__ . '/../../admin/db.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Photographe professionnelle – Katia Bulimar</title>
    <meta name="description"
        content="Photographe professionnelle à votre écoute pour sublimer vos moments : portraits, mariages, et shootings artistiques.">
    <link rel="canonical" href="https://www.katiabulimar.fr/photographie.php">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, viewport-fit=cover" />
    <link href="https://fonts.googleapis.com/css2?family=Castoro+Titling&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../style.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Nunito:wght@300;400;600&display=swap"
        rel="stylesheet" />
</head>

<body>
    <!-- Header -->
    <header>
        <!-- Bouton burger -->
        <button class="burger" aria-expanded="false" aria-controls="mainnav">
            <span></span><span></span><span></span>
        </button>
        <nav id="mainnav" class="nav container castoro-titling-regular" aria-label="Navigation principale">
            <a class="lienNav" href="../../index.php">ACCUEIL</a>
            <a class="lienNav" href="../maquillage/maquillage.php">MAQUILLAGE</a>
            <a class="lienNav" href="../coiffure/coiffure.php">COIFFURE</a>
            <a class="lienNav active" href="../photographie/photographie.php">PHOTOGRAPHIE</a>
            <a class="lienNav" href="../contact.php">CONTACT</a>
        </nav>
    </header>

    <main class="container-title">
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
                <!-- Titre -->
                <section class="hero-page castoro-titling-regular">
                    <h1 class="page-title">PHOTOGRAPHIE</h1>
                </section>

                <!-- Intro / description -->
                <section class="intro">
                    <span class="lato-regular-italic subtitle_preintro">Pour que ces moments restent toujours gravés
                    </span>
                </section>

                <!-- Tarifs -->
                <section class="tarifs">
                    <!-- Une seule colonne, la grille s’adaptera si tu ajoutes d’autres items -->
                    <div class="tarifs-grid-solo">

                        <?php 
             


            $cats = $pdo->query(
            "SELECT `id`, `name`, `slug` FROM `categories` WHERE type = 'photographie' ORDER BY `sort_order`, `id`"); while
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

                <!-- Comparatif portrait / intimiste -->
                <section class="comparatif">
                    <h2 class="section-title castoro-titling-regular">
                        PHOTO PORTRAIT OU INTIMISTE ?
                    </h2>

                    <article class="bloc-comparatif">
                        <h4 class="lato-regular">Portrait</h3>
                            <div class="bloc-texte">
                                <p class="txtcomparatif lato-regular">
                                    Et si vous preniez un moment rien que pour vous ? Que ce soit pour
                                    des photos professionnelles ou simplement pour vous redécouvrir,
                                    je suis là pour vous accompagner. La séance photo est précédée
                                    d’une mise en beauté complète (coiffure et maquillage) réalisée
                                    avec soin. Je vous accueille dans mon studio, je peux également me
                                    déplacer à votre domicile ou en extérieur pour capturer votre
                                    portrait et figer vos instants de vie les plus précieux. Chaque
                                    prestation inclut un minimum de 8 photos en haute définition,
                                    légèrement retouchées, envoyées en format numérique selon votre
                                    choix.</p>
                                <?php
$stmt = $pdo->prepare("
    SELECT name, image_path
    FROM photo_examples
    WHERE name IN ('portrait', 'intimiste')
");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$imgPortrait    = $rows['portrait']     ?? 'images/photo-portait-default.jpg';
$imgIntimiste = $rows['intimiste'] ?? 'images/photo-intimiste-default.jpg';
?>
                                <img src="../../admin/pages/photographie/<?= htmlspecialchars($imgPortrait, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Exemple de photo portrait" class="bloc-visuel"></img>
                            </div>
                    </article>

                    <article class="bloc-comparatif inverse">
                        <h4 class="lato-regular">Intimiste</h3>
                            <div class="bloc-texte">
                                <img src="../../admin/pages/photographie/<?= htmlspecialchars($imgIntimiste, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Exemple de photo intimiste" class="bloc-visuel"></img>
                                <p class="txtcomparatif lato-regular">
                                    À travers mes séances intimistes, je révèle votre beauté naturelle avec douceur et
                                    authenticité. Mon approche repose sur le respect, la bienveillance et l’écoute, afin
                                    de vous offrir un espace où vous pouvez vous sentir pleinement vous-même, en
                                    confiance, sans précipitation.
                                    <br>
                                    <br>
                                    Chaque détail est soigneusement pensé pour sublimer votre singularité.
                                    Que la séance ait lieu chez vous, dans votre cocon, ou à mon domicile, je vous
                                    accompagne étape par étape pour créer des images élégantes, artistiques et
                                    profondément personnelles.
                                    <br>
                                    <br>
                                    À l’issue de la séance, vous sélectionnez un minimum de 10 photos, retouchées avec
                                    soin et livrées en haute définition, au format numérique.
                                    <br>
                                    <br>
                                    Mon objectif va bien au-delà de la photographie :
                                    vous permettre de repartir avec une nouvelle confiance et un regard rempli de fierté
                                    sur votre propre beauté.
                                </p>
                            </div>
                    </article>
                    <!-- Bouton -->
                    <p class="container-btn">
                        <a class="btn lato-regular" href="galerie.php">Voir les photos</a>
                    </p>
                </section>
        </section>

    </main>

    <!-- Footer -->
    <footer>
        <div class="container-logo">
            <img class="logo" src="../../assets/logo3.png" alt="Logo Katia Bulimar" />
        </div>

        <div class="container-text">
            <div class="footer-links castoro-titling-regular">
                <a class="lien-footer" href="../../index.php">ACCUEIL</a> |
                <a class="lien-footer" href="../coiffure/coiffure.php">COIFFURE</a>
                |
                <a class="lien-footer" href="../maquillage/maquillage.php">MAQUILLAGE</a>
                |
                <a class="lien-footer" href="../photographie/photographie.php">PHOTOGRAPHIE</a>
                |
                <a class="lien-footer" href="../contact.php">CONTACT</a>
            </div>

            <span class="lora-italic">Katia Bulimar - Maquilleuse professionnelle</span>

            <span class="mentions">
                ©<span id="year"></span> Katia Bulimar — Tous droits réservés |
                <a class="mention-Rgpd" href="../RGPD.php">Mentions légales - RGPD</a>
            </span>
        </div>

        <div class="container-socials">
            <div class="socials" aria-label="Réseaux sociaux">
                <a href="#" aria-label="Instagram">
                    <img class="icon insta" src="../../assets/icons8-instagram-96.png" alt="Instagram" />
                </a>
                <a href="#" aria-label="Facebook">
                    <img class="icon f" src="../../assets/icons8-facebook-96.png" alt="Facebook" />
                </a>
            </div>
        </div>
    </footer>

    <script src="../../script.js"></script>
</body>

</html>