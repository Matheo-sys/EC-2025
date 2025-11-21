<?php
// Inclusions nécessaires (configuration de la base de données et header)
include('config/database.php');
include('includes/header.php');
?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        nonce="<?= $nonce ?>">



    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParisSport+ - privacy</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" nonce="<?= $nonce ?>">

    <!-- Polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"
        nonce="<?= $nonce ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="32x32">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="48x48">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="64x64">
</head>
<main class="container mt-5">
    <h1 class="text-center mb-4">Politique de confidentialité de ParisSport+</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>Introduction</h3>
                    <p>La présente politique de confidentialité explique comment ParisSport+ collecte, utilise et
                        protège vos données personnelles lorsque vous utilisez notre site web et nos services. Nous nous
                        engageons à respecter la confidentialité de vos données personnelles et à les protéger
                        conformément aux lois en vigueur, notamment le Règlement Général sur la Protection des Données
                        (RGPD).</p>

                    <h3>1. Données collectées</h3>
                    <p>Nous collectons les données suivantes lorsque vous utilisez notre site :</p>
                    <ul>
                        <li><strong>Données d'identification :</strong> telles que votre nom, prénom, adresse e-mail, et
                            toute autre information que vous fournissez lors de votre inscription ou mise à jour de
                            votre profil.</li>
                        <li><strong>Données de navigation :</strong> comme votre adresse IP, votre type de navigateur,
                            votre localisation géographique, et d'autres informations sur votre appareil, collectées via
                            des cookies ou des technologies similaires.</li>
                        <li><strong>Données d'activité :</strong> telles que votre historique de navigation sur le site,
                            les événements auxquels vous participez, et les informations liées à vos interactions avec
                            notre plateforme.</li>
                    </ul>

                    <h3>2. Utilisation des données</h3>
                    <p>Les données que nous collectons sont utilisées pour les finalités suivantes :</p>
                    <ul>
                        <li><strong>Gestion de votre compte :</strong> pour créer et gérer votre compte utilisateur, y
                            compris votre profil et vos informations de connexion.</li>
                        <li><strong>Personnalisation des services :</strong> pour vous offrir des recommandations, vous
                            informer sur des événements sportifs pertinents, et améliorer votre expérience utilisateur.
                        </li>
                        <li><strong>Communication :</strong> pour vous envoyer des notifications concernant les
                            événements, mises à jour, ou toute autre information relative à nos services.</li>
                        <li><strong>Amélioration de la plateforme :</strong> pour analyser et améliorer notre site en
                            fonction des retours et des comportements des utilisateurs.</li>
                    </ul>

                    <h3>3. Partage des données</h3>
                    <p>Nous ne partageons pas vos données personnelles avec des tiers, sauf dans les cas suivants :</p>
                    <ul>
                        <li><strong>Avec des prestataires de services :</strong> Nous pouvons partager vos données avec
                            des prestataires de services tiers qui nous aident à gérer notre site (hébergement, analyse
                            des données, services de paiement, etc.). Ces prestataires sont tenus de respecter la
                            confidentialité et la sécurité de vos données.</li>
                        <li><strong>Conformité légale :</strong> Nous pouvons divulguer vos données si cela est
                            nécessaire pour respecter une obligation légale ou en réponse à une demande légale d'une
                            autorité compétente.</li>
                    </ul>

                    <h3>4. Protection des données</h3>
                    <p>Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles pour protéger vos
                        données personnelles contre tout accès non autorisé, divulgation, modification ou destruction.
                        Cependant, aucune méthode de transmission ou de stockage électronique n'est totalement
                        sécurisée, et nous ne pouvons garantir une sécurité absolue.</p>

                    <h3>5. Cookies et technologies similaires</h3>
                    <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. Un cookie est un
                        fichier qui est enregistré sur votre appareil lorsque vous visitez notre site. Il nous permet de
                        vous reconnaître lors de vos prochaines visites et de personnaliser votre expérience.</p>
                    <p>Vous pouvez gérer vos préférences en matière de cookies dans les paramètres de votre navigateur.
                        Toutefois, veuillez noter que désactiver les cookies peut affecter certaines fonctionnalités du
                        site.</p>

                    <h3>6. Vos droits</h3>
                    <p>Conformément à la législation sur la protection des données personnelles, vous disposez des
                        droits suivants :</p>
                    <ul>
                        <li><strong>Droit d'accès :</strong> Vous pouvez demander une copie de vos données personnelles
                            que nous avons collectées.</li>
                        <li><strong>Droit de rectification :</strong> Vous pouvez demander la correction de données
                            personnelles incorrectes ou incomplètes.</li>
                        <li><strong>Droit à l'effacement :</strong> Vous pouvez demander la suppression de vos données
                            personnelles, sous réserve des exceptions légales.</li>
                        <li><strong>Droit de limitation :</strong> Vous pouvez demander la limitation du traitement de
                            vos données dans certaines circonstances.</li>
                        <li><strong>Droit d'opposition :</strong> Vous pouvez vous opposer au traitement de vos données
                            pour des raisons légitimes.</li>
                        <li><strong>Droit à la portabilité :</strong> Vous pouvez demander à recevoir vos données dans
                            un format structuré, couramment utilisé et lisible par machine.</li>
                    </ul>
                    <p>Pour exercer ces droits, vous pouvez nous contacter à l'adresse suivante : <a
                            href="mailto:parissport@alwaysdata.net">parissport@alwaysdata.net</a>.</p>

                    <h3>7. Conservation des données</h3>
                    <p>Nous conservons vos données personnelles aussi longtemps que nécessaire pour les finalités
                        décrites dans cette politique, et conformément à la législation en vigueur. En général, nous
                        conservons vos données pendant la durée de votre inscription et de votre utilisation de nos
                        services.</p>

                    <h3>8. Modifications de la politique</h3>
                    <p>Nous nous réservons le droit de modifier cette politique de confidentialité à tout moment. Toute
                        modification sera publiée sur cette page avec la date de mise à jour. Nous vous encourageons à
                        consulter régulièrement cette page pour vous tenir informé des dernières modifications.</p>

                    <h3>Contact</h3>
                    <p>Si vous avez des questions ou des préoccupations concernant cette politique de confidentialité,
                        vous pouvez nous contacter à l'adresse suivante : <a
                            href="mailto:parissport@alwaysdata.net">parissport@alwaysdata.net</a>.</p>

                    <p class="text-center">
                        <a href="index.php" class="btn btn-primary"
                            style="background-color: #2B9348; border-color: #2B9348;">Retour à l'accueil</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>