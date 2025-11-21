<?php
// Initialisation centralisée de la session (cookies sécurisés)
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/security.php';
?>
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
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
                    <li class="nav-item"><a href="demo_session_fixation.php" class="nav-link">Démo session</a></li>

                    <?php if (isset($_SESSION['user'])): ?>
                        <!-- Menu déroulant -->
                        <li class="nav-item dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none" id="userMenu"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="nav-link"><?= htmlspecialchars($_SESSION['user']['prenom']) ?></span>
                                <img src="<?= $_SESSION['user']['avatar'] ?>" alt="Avatar"
                                    style="width:32px; height:32px; border-radius:50%; margin-left: 8px;">
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="profile.php">Profil</a></li>
                                <li><a class="dropdown-item" href="mylikes.php">Favoris</a></li>
                                <?php if ($_SESSION['user']['role'] == 1): ?>
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
                    <li class="nav-item"><a href="demo_session_fixation.php" class="nav-link">Démo session</a></li>

                    <?php if (isset($_SESSION['user'])): ?>
                        <!-- Menu déroulant pour mobile -->
                        <li class="nav-item dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none" id="userMenuMobile"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="nav-link"><?= htmlspecialchars($_SESSION['user']['prenom']) ?></span>
                                <img src="<?= $_SESSION['user']['avatar'] ?>" alt="Avatar"
                                    style="width:32px; height:32px; border-radius:50%; margin-left: 8px;">
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userMenuMobile">
                                <li><a class="dropdown-item" href="profile.php">Profil</a></li>
                                <li><a class="dropdown-item" href="mylikes.php">Favoris</a></li>
                                <?php if ($_SESSION['user']['role'] == 1): ?>
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