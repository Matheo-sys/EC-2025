<?php include('includes/header.php'); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - ParisSport+</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        nonce="<?= $nonce ?>">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        nonce="<?= $nonce ?>"></script>

    <!-- Polices -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- CSS Perso -->
    <link rel="stylesheet" href="css/style.css" nonce="<?= $nonce ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png">
</head>

<body>

    <div class="container py-5 text-center">

        <h1 class="mb-4 fw-bold">À propos de ParisSport+</h1>

        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <p class="lead">
                    ParisSport+ est un site qui permet de localiser et trouver facilement des terrains de sport à Paris
                    en fonction de vos envies et de votre localisation.
                </p>
            </div>
        </div>

        <h2 class="mb-4 fw-semibold">Mon Parcours</h2>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <img src="assets/image cv.png" alt="Mathéo Soriano" class="img-fluid rounded-circle shadow mb-3"
                    style="width:200px; height:200px; object-fit:cover;">
                <h3 class="fw-bold">Mathéo Soriano</h3>
                <p>Développeur étudiant passionné par le sport et les nouvelles technologies. Ce projet a été réalisé
                    dans le cadre de mon cursus en développement web.</p>
            </div>
        </div>

        <hr class="my-5">

        <h2 class="mb-4 fw-semibold">Mes objectifs</h2>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <p>
                    L'objectif principal de ParisSport+ est de rendre le sport accessible à tous, en proposant un outil
                    simple, rapide et intuitif pour trouver des équipements sportifs proches de chez soi.
                </p>
            </div>
        </div>

        <hr class="my-5">

        <h2 class="mb-4 fw-semibold">Contact</h2>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <p>N'hesitez pas à me contacter ! </p>
                <a href="contact.php" class="btn btn-secondary w-10"
                    style="background-color: #2B9348; border-color: #2B9348;">Contact</a>
            </div>
        </div>

    </div>

</body>

</html>

<?php include('includes/footer.php'); ?>