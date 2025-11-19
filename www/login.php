<?php

// Si l'utilisateur est déjà connecté, redirige-le vers la page d'accueil (index.php)
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Le reste de ton code pour la gestion du formulaire de connexion
include('config/database.php'); 
include('includes/header.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],  
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'avatar' => $user['avatar'] ?? 'assets/default-avatar.png',
            'role' => $user['role']
        ];
        
        header("Location: index.php");
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
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
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="32x32">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="48x48">
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="64x64">    
</head>
<main class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 500px; width: 100%;">
        <h1 class="text-center mb-3">ParisSport+</h1>
        <h3 class="text-center mb-4">Connexion</h3>

        <?php if (!empty($erreur)) echo "<div class='alert alert-danger'>$erreur</div>"; ?>

        <form method="post">
            <div class="form-floating mb-3">
                <input type="email" class="form-control2 w-100 rounded-pill" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control2 w-100 rounded-pill" id="password" name="password" placeholder="Mot de passe" required>
            </div>

            <button type='submit' class="btn btn-secondary w-100 rounded-pill" style="background-color: #2B9348; border-color: #2B9348;">
                Se connecter
            </button>


            <p class="text-center mt-3 mb-0">Pas encore de compte ? 
                <a href="register.php" class="text-decoration-none text-primary fw-bold">S'inscrire</a>
            </p>
        </form>
    </div>
</main>

<?php include('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>