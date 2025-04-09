<?php
// Inclusions nécessaires (configuration de la base de données et header)
include('config/database.php');
include('includes/header.php');
?>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParisSport+ - Accueil</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="32x32">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="48x48">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="64x64">    
</head>
<main class="container mt-5">
    <h1 class="text-center mb-4">Mentions légales de ParisSport+</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>Identification de l'éditeur</h3>
                    <p><strong>Nom de l'entreprise :</strong> ParisSport+</p>
                    <p><strong>Siège social :</strong> 123 rue du Sport, 75001 Paris, France</p>
                    <p><strong>SIRET :</strong> 123 456 789 00012</p>
                    <p><strong>Numéro de TVA intracommunautaire :</strong> FR12345678901</p>
                    <p><strong>Directeur de la publication :</strong> Jean Dupont</p>

                    <h3>Hébergement</h3>
                    <p><strong>Nom de l'hébergeur :</strong> HébergeurWeb Inc.</p>
                    <p><strong>Adresse de l'hébergeur :</strong> 456 rue du Web, 75002 Paris, France</p>
                    <p><strong>Numéro de téléphone :</strong> +33 1 23 45 67 89</p>

                    <h3>Propriété intellectuelle</h3>
                    <p>Les contenus (textes, images, logos, vidéos, etc.) présents sur ParisSport+ sont protégés par les droits de propriété intellectuelle. Toute reproduction, diffusion ou utilisation non autorisée des éléments du site est interdite.</p>

                    <h3>Données personnelles</h3>
                    <p>ParisSport+ respecte la vie privée de ses utilisateurs. Nous collectons et traitons vos données personnelles conformément à notre <a href="privacy.php">politique de confidentialité</a>.</p>
                    <p>Nous utilisons la base de données du gouvernement pour vous fournir des informations fiables et actualisées sur les événements sportifs et les lieux à Paris. Vos informations personnelles sont traitées en toute sécurité et ne sont utilisées que dans le cadre des services proposés par ParisSport+.</p>

                    <h3>Responsabilité</h3>
                    <p>ParisSport+ met tout en œuvre pour offrir un service de qualité, mais ne peut garantir l'absence d'erreurs ou de bugs techniques. Nous déclinons toute responsabilité en cas d'interruption ou de mauvaise utilisation du site.</p>

                    <h3>Cookies</h3>
                    <p>Le site ParisSport+ utilise des cookies pour améliorer votre expérience utilisateur. Vous pouvez gérer vos préférences en matière de cookies dans les paramètres de votre navigateur.</p>

                    <h3>Contact</h3>
                    <p>Pour toute question, contactez-nous par e-mail à <a href="mailto:support@parissportplus.com">contact@parissportplus.com</a> ou par téléphone au +33 1 23 45 67 89.</p>

                    <p class="text-center">
                        <a href="index.php" class="btn btn-primary" style="background-color: #2B9348; border-color: #2B9348;">Retour à l'accueil</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>

