<?php 
include('config/database.php'); 
include('includes/header.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password'];

     if ($password !== $confirm_password) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } else {
       
        $sql = "SELECT COUNT(*) FROM utilisateurs WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $email_exists = $stmt->fetchColumn();

        if ($email_exists) {
            $erreur = "Cet email est déjà utilisé.";
        
    } else {
        // Si les mots de passe correspondent, on hache le mot de passe
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    


    $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if($stmt->execute([$nom, $prenom, $email, $password_hashed])) {
        $user_id = $conn->lastInsertId();
        
        $_SESSION['user_id'] = $user_id;
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['is_logged_in'] = true;
        $_SESSION['avatar_url'] = 'assets/default-avatar.png'; 
        
        header("Location: login.php");
        exit();
    } else {
        $erreur = "Erreur lors de l'inscription.";
    }
}}}


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
        <h3 class="text-center mb-4">Créer un compte</h3>

        <?php if (!empty($erreur)) echo "<div class='alert alert-danger'>$erreur</div>"; ?>

        <form method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control2 w-100 rounded-pill" id="nom" name="nom" placeholder="Nom" required>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control2 w-100 rounded-pill" id="prenom" name="prenom" placeholder="Prénom" required>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control2 w-100 rounded-pill" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control2 w-100 rounded-pill" id="password" name="password" placeholder="Mot de passe" required>
            </div>
            
            <div class="password-strength">
                <div class="strength-bar" id="strengthBar"></div>
                <p id="strengthText"></p>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control2 w-100 rounded-pill" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
            </div>

            <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="accept_conditions" name="accept_conditions" required>
            <label class="form-check-label" for="accept_conditions">
                J'accepte les <a href="terms.php" target="_blank">conditions d'utilisation</a> et la <a href="privacy.php" target="_blank">politique de confidentialité</a>.
            </label>
        </div>

            <button type='submit' id="submitBtn" class="btn btn-secondary w-100 rounded-pill" style="background-color: #2B9348; border-color: #2B9348;" disabled>
                S'inscrire
            </button>


            <p class="text-center mt-3 mb-0">Déjà un compte ? 
                <a href="login.php" class="text-decoration-none text-primary fw-bold">Connexion</a>
            </p>
        </form>
    </div>
</main>
<?php include('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>