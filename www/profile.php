<?php
session_start(); // Démarre la session


// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    echo "Utilisateur non connecté, redirection vers login.php";
    // Si l'utilisateur n'est pas connecté, redirige vers la page de connexion
    header('Location: login.php');
    exit();
}


// Connexion à la base de données
include('config/database.php');

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user']['id'];

// Préparer la requête pour obtenir les informations de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Vérifie si l'utilisateur existe dans la base de données
if (!$user) {
    echo "Utilisateur non trouvé dans la base de données, redirection vers login.php";
    header('Location: login.php');
    exit();
}
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
    
    <!-- Polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="32x32">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="48x48">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="64x64">    
</head>
<body>

<?php include('includes/header.php'); ?>

<main class="container mt-5">
    <h1 class="text-center mb-4">Mon Profil</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <!-- Affichage des informations de l'utilisateur -->
            <div class="card">
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <img src="<?= htmlspecialchars($user['avatar'] ?? 'assets/default-avatar.png') ?>" alt="Avatar" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">

                    <!-- Nom et Prénom -->
                    <h3 class="mt-3"><?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?></h3>
                    
                    <!-- Email -->
                    <p class="mt-2"><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
