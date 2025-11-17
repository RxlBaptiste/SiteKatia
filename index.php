<?php
require __DIR__ . '/admin/db.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- ===========================
     🌐 META SEO GLOBALES
=========================== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Katia Bulimar – Maquillage, Coiffure & Photographie</title>
    <meta name="description"
        content="Découvrez l’univers de Katia Bulimar, maquilleuse, coiffeuse et photographe professionnelle. Prestations sur mesure, mises en beauté et séances photo élégantes.">
    <meta name="keywords"
        content="maquillage, coiffure, photographie, maquilleuse professionnelle, shooting photo, beauté, mariage, Bretagne, Katia Bulimar">
    <meta name="author" content="Katia Bulimar">
    <meta name="robots" content="index, follow">
    <meta name="language" content="fr">
    <meta name="theme-color" content="#b1b5ae">

    <!-- ===========================
     📱 FAVICONS & APP ICONS
=========================== -->
    <link rel="icon" type="image/png" href="assets/logo3.png">
    <link rel="apple-touch-icon" href="assets/logo3.png">

    <!-- ===========================
     📘 OPEN GRAPH (FACEBOOK, LINKEDIN, etc.)
=========================== -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Katia Bulimar – Maquillage, Coiffure & Photographie">
    <meta property="og:description"
        content="Découvrez les prestations de Katia Bulimar : maquillage, coiffure et photographie. Un univers de beauté, d’élégance et de créativité.">
    <meta property="og:url" content="https://www.katiabulimar.fr/">
    <meta property="og:image" content="https://www.katiabulimar.fr/images/og-cover.jpg">
    <meta property="og:site_name" content="Katia Bulimar">
    <meta property="og:locale" content="fr_FR">

    <!-- ===========================
     🐦 TWITTER CARD
=========================== -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Katia Bulimar – Maquillage, Coiffure & Photographie">
    <meta name="twitter:description"
        content="Découvrez l’univers de Katia Bulimar, maquilleuse, coiffeuse et photographe professionnelle.">
    <meta name="twitter:image" content="https://www.katiabulimar.fr/images/og-cover.jpg">
    <meta name="twitter:creator" content="@katiabulimar">

    <!-- ===========================
     📊 STRUCTURED DATA (JSON-LD)
=========================== -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "Katia Bulimar",
        "jobTitle": "Maquilleuse, Coiffeuse & Photographe",
        "url": "https://www.katiabulimar.fr/",
        "image": "https://www.katiabulimar.fr/images/og-cover.jpg",
        "sameAs": [
            "https://www.instagram.com/katiabulimar",
            "https://www.facebook.com/katiabulimar"
        ],
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Bretagne",
            "addressCountry": "FR"
        },
        "description": "Katia Bulimar propose des prestations professionnelles de maquillage, coiffure et photographie pour vos événements et shootings."
    }
    </script>

    <!-- ===========================
     🔧 AUTRES
=========================== -->
    <link rel="canonical" href="https://www.katiabulimar.fr/">
    <meta name="copyright" content="© Katia Bulimar - Tous droits réservés.">
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
    <!-- Header -->
    <header>
        <!-- Bouton burger -->
        <button class="burger" aria-expanded="false" aria-controls="mainnav">
            <span></span><span></span><span></span>
        </button>
        <nav id="mainnav" class="nav container castoro-titling-regular" aria-label="Navigation principale">
            <a class="lienNav active">ACCUEIL</a>
            <a class="lienNav" href="pages/maquillage/maquillage.php">MAQUILLAGE</a>
            <a class="lienNav" href="pages/coiffure/coiffure.php">COIFFURE</a>
            <a class="lienNav" href="pages/photographie/photographie.php">PHOTOGRAPHIE</a>
            <a class="lienNav" href="pages/contact.php">CONTACT</a>
        </nav>
    </header>

    <!-- Hero -->
    <section class="hero container castoro-titling-regular">
        <section class="title-accueil">
            <h1>KATIA BULIMAR</h1>
            <div class="subtitle lato-regular">
                MAQUILLAGE
                <span class="dot-sub">|</span>
                COIFFURE
                <span class="dot-sub">|</span>
                PHOTOGRAPHIE
            </div>
        </section>
        <?php 
function public_url_from_db(string $path): string {
    $p = trim((string)$path);
    $p = str_replace('\\', '/', $p);

    // déjà absolu ? on ne touche pas
    if (preg_match('~^https?://~i', $p)) {
        return $p;
    }

    // nettoie les ../ et ./
    while (strpos($p, '../') === 0) {
        $p = substr($p, 3);
    }
    if (strpos($p, './') === 0) {
        $p = substr($p, 2);
    }

    // depuis la page publique il faut préfixer "admin/"
    if (!str_starts_with($p, 'admin/')) {
        $p = 'admin/' . ltrim($p, '/');
    }

    // IMPORTANT : pas de "/" initial ici
    return ltrim($p, '/');
}
?>
        <!-- Carrousel photos (cards slider) -->
        <div class="carousel-wrapper">
            <div class="carousel" aria-label="Galerie photos">
                <?php 
              $sql = $pdo->query("SELECT * FROM carousel ORDER BY id");
              ($images = $sql->fetchAll()); 
              foreach ($images as $img): 
              $src = public_url_from_db($img['curent_image_url']);?>
                <!-- Carrousel photos (cards slider) -->
                <div class="card">
                    <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"></img>
                </div>
                <?php endforeach; ?>
                <!-- Duplication des photos -->
                <?php 
              $sql = $pdo->query("SELECT * FROM carousel ORDER BY id ASC LIMIT 4");
              ($images = $sql->fetchAll()); 
              foreach ($images as $img):
              $src = public_url_from_db($img['curent_image_url']); ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"></img>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Présentation -->
    <section class="presentation container" id="presentation">
        <h2 class="title_presentation castoro-titling-regular">PRÉSENTATION</h2>
        <span class="subtitle_presentation lato-regular-italic">Mon travail d'artiste consiste à montrer aux femmes la
            beauté en elles qui a toujours existé mais qu'elles n'ont jamais remarqué.</span>
        <?php
$stmt = $pdo->query("SELECT profil_image FROM company_profile LIMIT 1");
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

$image = $profile['profil_image'] ?? 'images/default-profil.jpg'; // fallback
?>
        <div class="cols">
            <img src="admin/<?= htmlspecialchars($image) ?>" alt="Photo de profil de Katia Bulimar" class="portrait">
            </img>

            <div>
                <p class="pres-text lato-regular">
                    Je m’appelle Katia Bulimar, et je suis passionnée par l’art du
                    maquillage. J’ai suivi une formation de makeup artist à Toulouse, où
                    j’ai passé une année à explorer et maîtriser le maquillage sous
                    toutes ses formes. Diplômée, je suis retournée dans ma région
                    natale, la Bretagne, avec une ambition claire : créer ma propre
                    entreprise et vivre de ma passion. <br />
                    <br />
                    Pour proposer une expérience complète, je me suis également formée
                    au coiffage de mariée et j’ai obtenu mon certificat. Très vite, je
                    me suis naturellement tournée vers ce qui me touche profondément :
                    révéler la beauté naturelle des femmes, sans artifices excessifs.
                    <br />
                    <br />
                    En côtoyant mon entourage, j’ai constaté à quel point de nombreuses
                    femmes manquent de confiance en elles. Cela m’a poussée à me former
                    à la photographie, afin d’offrir des séances qui allient mise en
                    beauté et valorisation de soi à travers l’image. <br />
                    <br />
                    Aujourd’hui, mon entreprise me ressemble : humaine, bienveillante et
                    portée par les valeurs de sororité et d’authenticité. Je propose des
                    prestations sur mesure pour tous types d’événements : mariages,
                    cours d’auto-maquillage, mises en beauté… mais aussi des séances de
                    photo-thérapie. Car chaque femme mérite de se voir telle qu’elle est
                    : belle, forte et unique.
                </p>
            </div>
        </div>
        <!-- Badges de certification (non cliquables) -->
        <div class="badges" aria-label="Certifications et mentions">
            <span class="badge lato-regular">
                <img class="dot" src="assets/medaille.png" />
                Maquilleuse certifié</span>
            <span class="badge lato-regular"><img class="dot" src="assets/coeur.png" /><span>Spécialiste en beauté
                    nuptiale</span></span>
            <span class="badge lato-regular"><img class="dot" src="assets/etoile.png" />Produits haut de
                gamme</span>
        </div>
        <!-- Vidéo -->
        <div class="video" aria-label="Présentation en vidéo">
            <!-- Remplace l'URL de la vidéo -->
            <?php
$stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'homepage_video'");
$stmt->execute();
$videoUrl = $stmt->fetchColumn() ?: '';

// Petite fonction pour transformer l’URL en lien embed
function youtubeToEmbed(string $url): ?string {
    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/))([a-zA-Z0-9_-]{11})~', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    return null;
}

$embedUrl = youtubeToEmbed($videoUrl);
?>

            <?php if ($embedUrl): ?><iframe class="VideoYT" width="1060" height="490"
                src="<?= htmlspecialchars($embedUrl, ENT_QUOTES, 'UTF-8') ?>" title="Présentation"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
            <?php endif; ?>
        </div>
    </section>
    <!-- Prestations -->
    <section class="services container" id="prestations">
        <h2 class="title_white section-title castoro-titling-regular">MES PRESTATIONS</h2>
        <div class="service-grid">
            <article class="service" id="serviceMaquillage">
                <img class="thumb" src="assets/maquillage.jpg" alt="Maquillage – visuel de démonstration" />
                <a class="subtitleArticle lato-regular" href="pages/maquillage/maquillage.php">
                    Maquillage
                </a>
            </article>

            <article class="service" id="serviceCoiffure">
                <img class="thumb" src="assets/image0.jpeg" alt="Coiffure – visuel de démonstration" />
                <a class="subtitleArticle lato-regular" href="pages/coiffure/coiffure.php">
                    Coiffure
                </a>
            </article>

            <article class="service" id="servicePhotographie">
                <img class="thumb" src="assets/photographie.jpg" alt="Photographie – visuel de démonstration" />
                <a class="subtitleArticle lato-regular" href="pages/photographie/photographie.php">
                    Photographie
                </a>
            </article>
        </div>
    </section>

    <!-- Avis (carrousel de cartes) -->
    <section class="reviews container" id="avis">
        <h2 class="section-title castoro-titling-regular">LES AVIS</h2>

        <div class="carousels" aria-label="Avis clients">
            <?php 
              $sql = $pdo->query(" SELECT id, stars, pending_avis, curent_avis FROM avis ORDER BY id ASC");
            while($a = $sql->fetch()) {
                $stars = (int)$a['stars'];
            ?>
            <article class="review-card">
                <div class="stars">
                    <?php 
                    for ($i = 1; $i <= 5; $i++) echo $i <= $stars ? '★' : '☆'; // étoile pleine ou vide
                ?></div>
                <div class="review-text lora">
                    <?= htmlspecialchars($a['curent_avis']) ?>
                </div>
            </article>
            <?php } ?>
            <!-- Duplication -->
            <?php 
              $sql = $pdo->query(" SELECT id, stars, pending_avis, curent_avis FROM avis ORDER BY id ASC LIMIT 4");
            while($a = $sql->fetch()) {
                $stars = (int)$a['stars'];
            ?>
            <article class="review-card">
                <div class="stars">
                    <?php 
                    for ($i = 1; $i <= 5; $i++) echo $i <= $stars ? '★' : '☆'; // étoile pleine ou vide
                ?></div>
                <div class="review-text lora">
                    <?= htmlspecialchars($a['curent_avis']) ?>
                </div>
            </article>
            <?php } ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container-logo">
            <img class="logo" src="assets/logo3.png" />
        </div>
        <div class="container-text">
            <div class="footer-links castoro-titling-regular">
                <a class="lien-footer" href="index.php">ACCUEIL</a> |
                <a class="lien-footer" href="pages/coiffure/coiffure.php">COIFFURE</a>
                |
                <a class="lien-footer" href="pages/maquillage/maquillage.php">MAQUILLAGE</a>
                |
                <a class="lien-footer" href="pages/photographie/photographie.php">PHOTOGRAPHIE</a>
                |
                <a class="lien-footer" href="pages/contact.php">CONTACT</a>
            </div>
            <span class="lora-italic">Katia Bulimar - Maquilleuse professionnelle</span>
            <span class="mentions">
                ©<span id="year"></span> Katia Bulimar — Tous droits réservés |
                <a class="mention-Rgpd" href="pages/RGPD.php">Mentions légales - RGPD</a>
            </span>
        </div>
        <div class="container-socials">
            <div class="socials" aria-label="Réseaux sociaux">
                <a href="#" aria-label="Instagram">
                    <!-- Instagram (simple) -->
                    <img class="icon insta" src="assets/icons8-instagram-96.png" />
                </a>
                <a href="#" aria-label="Facebook">
                    <!-- Facebook (simple) -->
                    <img class="icon f" src="assets/icons8-facebook-96.png" />
                </a>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
    <script>
    window.addEventListener("load", () => {
        window.scrollTo(0, 0); // remet la page tout en haut à gauche
        document.querySelectorAll(".carousel").forEach((carousel) => {
            carousel.scrollLeft = 0; // remet le carrousel au début
        });
    });

    //Redirection vers les prestation

    // On récupère la div
    document
        .getElementById("serviceCoiffure")
        .addEventListener("click", () => {
            // On redirige vers une page interne
            window.location.href = "pages/coiffure/coiffure.php";
        });
    // On récupère la div
    document
        .getElementById("serviceMaquillage")
        .addEventListener("click", () => {
            // On redirige vers une page interne
            window.location.href = "pages/maquillage/maquillage.php";
        });
    // On récupère la div
    document
        .getElementById("servicePhotographie")
        .addEventListener("click", () => {
            // On redirige vers une page interne
            window.location.href = "pages/photographie/photographie.php";
        });
    </script>
</body>

</html>