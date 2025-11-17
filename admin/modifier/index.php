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



// ---- TRAITEMENT DU FORMULAIRE ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['homepage_video'] ?? '');

    $stmt = $pdo->prepare("
        INSERT INTO settings (setting_key, setting_value)
        VALUES ('homepage_video', :value)
        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
    ");
    $stmt->execute(['value' => $url]);

    header("Location: index.php?updated=1");
    exit;
}

// ---- RÉCUPÉRATION DE LA VALEUR ACTUELLE ----
$stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'homepage_video'");
$stmt->execute();
$currentVideo = $stmt->fetchColumn() ?: '';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Espace administrateur du site Katia Bulimar. Accès réservé.">

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, viewport-fit=cover" />
    <title>Katia Bulimar – Maquillage • Coiffure • Photographie</title>
    <link href="https://fonts.googleapis.com/css2?family=Castoro+Titling&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../style.css" />
    <!-- Polices -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Nunito:wght@300;400;600&display=swap"
        rel="stylesheet" />
    <style>
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

    .btn:hover {
        background: #8b9b86;
    }

    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(3px);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modal-content {
        background: white;
        padding: 25px;
        border-radius: 10px;
        width: min(90%, 500px);
        position: relative;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        cursor: pointer;
    }
    </style>
</head>

<body class="<?= isset($_SESSION['pseudo']) ? 'admin' : '' ?>">
    <!-- Header -->
    <header>
        <!-- Bouton burger -->
        <button class="burger" aria-expanded="false" aria-controls="mainnav">
            <span></span><span></span><span></span>
        </button>
        <div class="bandeau_admin">
            <section><button class="btn_nav_bd"><a class="logout-btn" aria-label="Se déconnecter"
                        href="../logout.php">Déconnexion</a></button></section>
            <section>Conecter en tant que : <?= htmlspecialchars($_SESSION['pseudo'], ENT_QUOTES, 'UTF-8') ?></section>
        </div>
        <nav id="mainnav" class="nav container castoro-titling-regular" aria-label="Navigation principale">
            <a class="lienNav active">ACCUEIL</a>
            <a class="lienNav" href="../pages/maquillage/maquillage.php">MAQUILLAGE</a>
            <a class="lienNav" href="../pages/coiffure/coiffure.php">COIFFURE</a>
            <a class="lienNav" href="../pages/photographie/photographie.php">PHOTOGRAPHIE</a>
            <a class="lienNav" href="../pages/contact.php">CONTACT</a>

            <button class="btn_nav_bd"><a class="nav-logout" aria-label="Se déconnecter"
                    href="../logout.php">Déconnexion</a></button>
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
        <section class="container_admin"><a href="carrousel.php" class="btn_bd_inv">Modifier</a></section>

        <div class="carousel" aria-label="Galerie photos">
            <div class="carousel" aria-label="Galerie photos">
                <?php 
              $sql = $pdo->query("SELECT * FROM carousel ORDER BY id");
              ($images = $sql->fetchAll()); 
              foreach ($images as $img): ?>
                <!-- Carrousel photos (cards slider) -->
                <div class="card">
                    <img src="<?= htmlspecialchars($img['curent_image_url']) ?>"></img>
                </div>
                <?php endforeach; ?>
                <!-- Duplication des photos -->
                <?php 
              $sql = $pdo->query("SELECT * FROM carousel ORDER BY id ASC LIMIT 4");
              ($images = $sql->fetchAll()); 
              foreach ($images as $img): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($img['curent_image_url']) ?>"></img>
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

        <div class="cols">
            <img src="../../assets/photos/profil.jpeg" class="portrait">
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
                <img class="dot" src="../../assets/medaille.png" />
                Maquilleuse certifié</span>
            <span class="badge lato-regular"><img class="dot" src="../../assets/coeur.png" /><span>Spécialiste en beauté
                    nuptiale</span></span>
            <span class="badge lato-regular"><img class="dot" src="../../assets/etoile.png" />Produits haut de
                gamme</span>
        </div>
        <!-- Vidéo -->
        <!-- Bouton pour ouvrir la modale -->
        <section class="container_admin">
            <button class="btn_bd_inv" id="openModal">Changer la vidéo YouTube</button>
        </section>
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
        </sectionQ>
        <!-- Prestations -->
        <section class="services container" id="prestations">
            <h2 class="title_white section-title castoro-titling-regular">MES PRESTATIONS</h2>
            <div class="service-grid">
                <article class="service" id="serviceMaquillage">
                    <img class="thumb" src="../../assets/maquillage.jpg" alt="Maquillage – visuel de démonstration" />
                    <a class="subtitleArticle lato-regular" href="../../pages/maquillage/maquillage.php">
                        Maquillage
                    </a>
                </article>

                <article class="service" id="serviceCoiffure">
                    <img class="thumb" src="../../assets/image0.jpeg" alt="Coiffure – visuel de démonstration" />
                    <a class="subtitleArticle lato-regular" href="../../pages/coiffure/coiffure.php">
                        Coiffure
                    </a>
                </article>

                <article class="service" id="servicePhotographie">
                    <img class="thumb" src="../../assets/photographie.jpg"
                        alt="Photographie – visuel de démonstration" />
                    <a class="subtitleArticle lato-regular" href="../../pages/photographie/photographie.php">
                        Photographie
                    </a>
                </article>
            </div>
        </section>

        <!-- Avis (carrousel de cartes) -->
        <section class="reviews container" id="avis">
            <h2 class="section-title castoro-titling-regular">LES AVIS</h2>

            <section class="container_admin"><a href="avis.php" class="btn_bd_inv">MODIFIER</a></section>


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
        <!-- MODALE -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <h2>Changer la vidéo YouTube</h2>

                <form method="post">
                    <label for="homepage_video">URL de la vidéo YouTube</label>
                    <input type="text" id="homepage_video" name="homepage_video"
                        value="<?= htmlspecialchars($currentVideo, ENT_QUOTES, 'UTF-8') ?>"
                        style="width:100%;max-width:600px;">
                    <button class="btn" type="submit">Enregistrer</button>
                </form>

                <span id="closeModal" class="close">&times;</span>
            </div>
        </div>
        <!-- Footer -->
        <footer>
            <div class="container-logo">
                <img class="logo" src="../../assets/logo3.png" />
            </div>
            <div class="container-text">
                <div class="footer-links castoro-titling-regular">
                    <a class="lien-footer" href="index.php">ACCUEIL</a> |
                    <a class="lien-footer" href="../pages/coiffures/coiffure.php">COIFFURE</a>
                    |
                    <a class="lien-footer" href="../pages/maquillage/maquillage.php">MAQUILLAGE</a>
                    |
                    <a class="lien-footer" href="../pages/photographie/photographie.php">PHOTOGRAPHIE</a>
                    |
                    <a class="lien-footer" href="../pages/contact.php">CONTACT</a>
                </div>
                <span class="lora-italic">Katia Bulimar - Maquilleuse professionnelle</span>
                <span class="mentions">
                    ©<span id="year"></span> Katia Bulimar — Tous droits réservés |
                    <a class="mention-Rgpd" href="../pages/RGPD.php">Mentions légales - RGPD</a>
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

        <script src="../../script.js"></script>
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
        <script>
        const modal = document.getElementById('modal');
        const openBtn = document.getElementById('openModal');
        const closeBtn = document.getElementById('closeModal');

        openBtn.onclick = () => modal.style.display = 'flex';
        closeBtn.onclick = () => modal.style.display = 'none';

        window.onclick = (e) => {
            if (e.target === modal) modal.style.display = 'none';
        };
        </script>
</body>

</html>