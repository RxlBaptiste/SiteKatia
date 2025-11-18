<?php
require __DIR__ . '/../admin/db.php';

$profile = $pdo->query("SELECT * FROM company_profile WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Contact – Katia Bulimar</title>
    <meta name="description"
        content="Contactez Katia Bulimar pour vos prestations de maquillage, coiffure ou photographie. Obtenez un devis personnalisé.">
    <link rel="canonical" href="https://www.katiabulimar.fr/contact.php">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, viewport-fit=cover" />
    <link href="https://fonts.googleapis.com/css2?family=Castoro+Titling&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css" />
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
            <a class="lienNav" href="../index.php">ACCUEIL</a>
            <a class="lienNav" href="./maquillage/maquillage.php">MAQUILLAGE</a>
            <a class="lienNav" href="./coiffure/coiffure.php">COIFFURE</a>
            <a class="lienNav" href="./photographie/photographie.php">PHOTOGRAPHIE</a>
            <a class="lienNav active" href="./contact.php">CONTACT</a>
        </nav>
    </header>

    <main class="container-title-contact">
        <!-- Titre -->
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
        </section>
        <hr class="hr" />
        <section class="hero-page castoro-titling-regular">
            <h1 class="page-title">CONTACT</h1>
        </section>

        <!-- Coordonnées -->
        <section class="intro">
            <p class="lato-regular">
                <strong>Je suis à votre écoute pour toute demande :</strong>
            </p>

            <p class="lato-regular">
                <img class="img-contact" src="../assets/mail arobaseBlack.png" />
                <a class="mail" href="mailto:katia.bulimar@yahoo.com">katia.bulimar@yahoo.com</a>
            </p>
            <p class="lato-regular">
                <img class="img-contact" src="../assets/phoneBlack.png" />
                <a class="tel" href="tel:<?php echo $profile['phone']; ?>"> <?php echo $profile['phone']; ?></a>
            </p>
            <p class="lato-regular">
                <img class="img-contact" src="../assets/loc-black.png" /> Basée près
                de Lamballe, Bretagne
            </p>
        </section>

        <!-- Carte -->
        <section class="wrap-map">
            <div class="video" aria-label="Localisation (carte)">
                <iframe class="map" src="https://www.google.com/maps?q=48.468,-2.514&z=10&hl=fr&output=embed"
                    width="50%" height="320px" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>

        <!-- Réseaux sociaux -->
        <section class="comparatifs sections_sociaux">
            <h2 class="lora suivre">Suivez-moi aussi sur les réseaux :</h2>
            <div class="socials-contact" aria-label="Réseaux sociaux">
                <a href="https://www.facebook.com/share/1Fx32vq1Sn/?mibextid=wwXIfr" aria-label="Facebook">
                    <img class="icon f" src="../assets/icons8-facebook-96.png" alt="Facebook" />
                </a>
                <a href="#" aria-label="Instagram">
                    <img class="icon insta" src="../assets/icons8-instagram-96.png" alt="Instagram" />
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container-logo">
            <img class="logo" src="../assets/logo3.png" alt="Logo Katia Bulimar" />
        </div>

        <div class="container-text">
            <div class="footer-links castoro-titling-regular">
                <a class="lien-footer" href="../index.php">ACCUEIL</a> |
                <a class="lien-footer" href="./maquillage/maquillage.php">MAQUILLAGE</a>
                |
                <a class="lien-footer" href="./coiffure/coiffure.php">COIFFURE</a> |
                <a class="lien-footer" href="./photographie/photographie.php">PHOTOGRAPHIE</a>
                |
                <a class="lien-footer" href="./contact.php">CONTACT</a>
            </div>

            <span class="lora-italic">Katia Bulimar - Maquilleuse professionnelle</span>

            <span class="mentions">
                © <span id="year"></span> Katia Bulimar — Tous droits réservés |
                <a class="mention-Rgpd" href="./RGPD.php">Mentions légales - RGPD</a>
            </span>
        </div>

        <div class="container-socials">
            <div class="socials" aria-label="Réseaux sociaux">
                <a href="https://www.instagram.com/katia.bulimar?igsh=bmoxYzF1bDNlb3F5&utm_source=qr"
                    aria-label="Instagram">
                    <img class="icon insta" src="../assets/icons8-instagram-96.png" alt="Instagram" />
                </a>
                <a href="https://www.facebook.com/share/1Fx32vq1Sn/?mibextid=wwXIfr" aria-label="Facebook">
                    <img class="icon f" src="../assets/icons8-facebook-96.png" alt="Facebook" />
                </a>
            </div>
        </div>
    </footer>

    <script src="../script.js"></script>
</body>

</html>