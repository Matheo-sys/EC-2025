<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
<!-- Header -->

<header class="header">
    <div class="container-fluid">
        <div class="row align-items-center">

            <!-- Logo -->
            <div class="col-6 col-sm-4 col-md-3">
                <a href="/" class="logo">
                    <img src="assets/P green-removebg-preview.png" alt="Logo" class="logo-img" sizes="64x64">
                </a>
                <a class="logo-text">ParisSport+</a>
            </div>

            <!-- Burger -->
            <div class="col-6 d-md-none text-end">
                <button class="navbar-toggler" type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#mobileMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="col-6 col-md-9 d-none d-md-block">
                <ul class="nav justify-content-end">
                    <li class="nav-item"><a href="/" class="nav-link">Accueil</a></li>
                    <li class="nav-item"><a href="map.php" class="nav-link">Carte</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">À propos</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>

                    <?php if(isset($_SESSION['user'])): ?>
                        <!-- Menu déroulant -->
                        <li class="nav-item dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="nav-link"><?= htmlspecialchars($_SESSION['user']['prenom']) ?></span>
                                <img src="<?= $_SESSION['user']['avatar'] ?>" alt="Avatar" style="width:32px; height:32px; border-radius:50%; margin-left: 8px;">
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="profile.php">Profil</a></li>
                                <li><a class="dropdown-item" href="mylikes.php">Favoris</a></li>
                                <?php if($_SESSION['user']['id'] == 5): ?>
                                    <li><a class="dropdown-item" href="admin_crud.php">Administration</a></l>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="logout.php">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a href="login.php" class="nav-link">Connexion/Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>

        <!-- Mobile Menu -->
        <div class="collapse navbar-collapse" id="mobileMenu">
            <div class="card card-body">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="/" class="nav-link">Accueil</a></li>
                    <li class="nav-item"><a href="map.php" class="nav-link">Carte</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">À propos</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                    
                    <?php if(isset($_SESSION['user'])): ?>
                        <!-- Menu déroulant pour mobile -->
                        <li class="nav-item dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none" id="userMenuMobile" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="nav-link"><?= htmlspecialchars($_SESSION['user']['prenom']) ?></span>
                                <img src="<?= $_SESSION['user']['avatar'] ?>" alt="Avatar" style="width:32px; height:32px; border-radius:50%; margin-left: 8px;">
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userMenuMobile">
                                <li><a class="dropdown-item" href="profile.php">Profil</a></li>
                                <li><a class="dropdown-item" href="mylikes.php">Favoris</a></li>
                                <?php if($_SESSION['user']['id'] == 5): ?>
                                    <li><a class="dropdown-item" href="admin_crud.php">Administration</a></l>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="logout.php">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a href="login.php" class="nav-link">Connexion/Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    </div>
</header>