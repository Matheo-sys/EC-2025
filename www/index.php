<?php
require_once("config/database.php");

$sql = $conn->query("SELECT * FROM equipements_sportifs_paris LIMIT 10");
$terrains = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php include('includes/head.php'); ?>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <main id="main-content">
        <!-- Section Recherche -->
        <section class="hero py-5">
            <div class="container text-center">
                <h1 class="mb-4">Bienvenue sur ParisSport+</h1>

                <form action="map.php" method="get" class="d-flex justify-content-center">
                    <div class="input-group flex-nowrap flex-md-wrap">
                        <input type="text" name="q" class="form-control" placeholder="Rechercher un terrain sportif..."
                            required>
                        <button class="btn btn-primary" style="background-color: #2B9348; border-color:#2B9348"
                            type="submit" href="map.php">Rechercher</button>
                    </div>
                </form>

                <p class="description mt-4" style="color: white;">
                    Trouvez facilement des terrains sportifs à Paris et en Ile-de-France.
                </p>
            </div>
        </section>


        <!-- Section Features -->
        <section class="features">
            <div class="container">
                <h2>Nos atouts</h2>
                <div class="grid">
                    <article class="feature-card">
                        <h3>Facilité d'accès</h3>
                        <p>Nous vous permettons de trouver rapidement des terrains de sport gratuits près de chez vous
                            grâce à une interface simple et intuitive.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Élargissement de l'offre</h3>
                        <p>Notre plateforme répertorie une grande variété de terrains de sport gratuits, couvrant divers
                            sports pour satisfaire tous les passionnés.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Accessibilité pour tous</h3>
                        <p>Tous les terrains listés sont accessibles sans frais, permettant à chacun de pratiquer son
                            sport préféré sans contrainte financière.</p>
                    </article>
                </div>
            </div>
        </section>



        <!-- Section Équipements -->
        <section class="container py-5">
            <h2 class="mb-4 text-center">Équipements sportifs à Paris</h2>

            <div id="carouselEquipements" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

                    <?php foreach ($terrains as $index => $terrain): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($terrain['nom']) ?></h5>
                                        <p class="card-text">Adresse : <?= htmlspecialchars($terrain['adresse']) ?></p>
                                        <p class="card-text">Sport : <?= htmlspecialchars($terrain['type_sport']) ?></p>
                                        <p class="card-text">Arrondissement :
                                            <?= htmlspecialchars($terrain['arrondissement']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselEquipements"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" style="background-color: #2B9348;"></span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#carouselEquipements"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" style="background-color: #2B9348;"></span>
                </button>


            </div>
        </section>



        <section class="cta" id="cta">
            <div class="container">
                <h2>Il manque un terrain, un sport ?</h2>
                <p>N'hésitez pas à nous contacter</p>
                <a href="contact.php" class="btn">Nous contacter</a>
            </div>
        </section>
    </main>

    <?php
    include('includes/footer.php');
    ?>
    <!-- JavaScript -->
    <script src="js/script.js" nonce="<?= $nonce ?>"></script>

</body>

</html>