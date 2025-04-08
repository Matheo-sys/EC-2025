<?php
require_once("config/database.php");

$sql = $conn->query("SELECT * FROM equipements_sportifs_paris LIMIT 10");
$terrains = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
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
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Polices (à personnaliser) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Favicon (à ajouter) -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="32x32">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="48x48">
    <link rel="icon" type="image/png" href="" sizes="64x64">    
</head>
<body>
    <!-- Préchargeur (optionnel) -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- Barre de navigation -->    

    <!-- Header -->
    <header class="header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="col-6 col-sm-4 col-md-3">
                <a href="/" class="logo">
                    <img src="assets/P green-removebg-preview.png" alt="Logo" class="logo-img" sizes="64x64">
                    <a class="logo-text">ParisSport+</a>
                </a>
            </div>

            <!-- Navigation Desktop -->
            <nav class="col-6 col-md-9 d-none d-md-block">
                <ul class="nav justify-content-end">
                    <li class="nav-item">
                        <a href="/" class="nav-link active">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.html" class="nav-link">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a href="services.html" class="nav-link">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="contact.html" class="nav-link">Contact</a>
                    </li>
                </ul>
            </nav>

            <!-- Burger Menu for Mobile -->
            <div class="col-6 d-md-none">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="/" class="nav-link">Accueil</a>
            </li>
            <li class="nav-item">
                <a href="about.html" class="nav-link">À propos</a>
            </li>
            <li class="nav-item">
                <a href="services.html" class="nav-link">Services</a>
            </li>
            <li class="nav-item">
                <a href="contact.html" class="nav-link">Contact</a>
            </li>
        </ul>
    </div>
</header>



    <main id="main-content">
        <!-- Section Recherche -->
        <section class="hero py-5">
            <div class="container text-center">
                <h1 class="mb-4">Bienvenue sur ParisSport+</h1>

                <form action="recherche.php" method="get" class="d-flex justify-content-center">
                    <div class="input-group w-50">
                        <input type="text" name="q" class="form-control" placeholder="Rechercher un terrain sportif..." required>
                        <button class="btn btn-primary" type="submit">Rechercher</button>
                    </div>
                </form>

                <p class="description mt-4" style="color: white;">
                    Trouvez facilement des terrains sportifs à Paris.
                </p>
            </div>
        </section>


        <!-- Section Features -->
        <section class="features">
            <div class="container">
                <h2>Nos atouts</h2>
                <div class="grid">
                    <article class="feature-card">
                        <h3>Atout 1</h3>
                        <p>Description courte de cet atout principal.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Atout 2</h3>
                        <p>Description courte de cet atout principal.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Atout 3</h3>
                        <p>Description courte de cet atout principal.</p>
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
                            <p class="card-text">Arrondissement : <?= htmlspecialchars($terrain['arrondissement']) ?></p>
                        </div>
                        </div>
                    </div>
                    </div>
                <?php endforeach; ?>

                </div>

                <!-- Boutons -->
                <!-- Boutons -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselEquipements" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" style="background-color: #2B9348;"></span> <!-- Change la couleur ici -->
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#carouselEquipements" data-bs-slide="next">
                <span class="carousel-control-next-icon" style="background-color: #2B9348;"></span> <!-- Change la couleur ici -->
                </button>


            </div>
        </section>



        <section class="cta" id="cta">
            <div class="container">
                <h2>Prêt à commencer ?</h2>
                <p>Une phrase d'accroche pour inciter à l'action</p>
                <a href="contact.html" class="btn">Nous contacter</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-columns">
                <!-- Colonne 1 -->
                <div class="footer-col">
                    <h4>À propos</h4>
                    <p>Une brève description de votre entreprise ou projet.</p>
                </div>

                <!-- Colonne 2 -->
                <div class="footer-col">
                    <h4>Liens utiles</h4>
                    <ul>
                        <li><a href="mentions-legales.html">Mentions légales</a></li>
                        <li><a href="confidentialite.html">Confidentialité</a></li>
                    </ul>
                </div>

                <!-- Colonne 3 -->
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul>
                        <li>Email: <a href="mailto:contact@example.com">contact@example.com</a></li>
                        <li>Tél: <a href="tel:+33123456789">01 23 45 67 89</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="copyright">
                <p>© 2023 Nom du Site. Tous droits réservés.</p>
                <div class="social-links">
                    <a href="#"><img src="images/facebook-icon.svg" alt="Facebook"></a>
                    <a href="#"><img src="images/twitter-icon.svg" alt="Twitter"></a>
                    <a href="#"><img src="images/linkedin-icon.svg" alt="LinkedIn"></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="js/script.js"></script>
    <script>
  const track = document.querySelector('.carousel-track');
  const clones = track.innerHTML;
  track.innerHTML += clones; // Double les cartes pour loop infini
</script>

</body>
</html>