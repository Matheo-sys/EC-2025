<?php
// Inclure l'initialisation de la session avant toute utilisation de $_SESSION
include('includes/session.php');

// Si l'utilisateur est déjà connecté, redirige-le vers la page d'accueil (index.php)
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include('includes/logger.php');

// Le reste de ton code pour la gestion du formulaire de connexion
include('config/database.php');
include('includes/header.php');
include('includes/csrf.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification CSRF
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        write_log('CSRF', 'Unknown', 'FAILURE', 'Invalid token on Login');
        die("Erreur de sécurité (CSRF). Veuillez recharger la page.");
    }

    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'avatar' => $user['avatar'] ?? 'assets/default-avatar.png',
                'role' => $user['role']
            ];

            write_log('LOGIN', $email, 'SUCCESS', 'Role: ' . $user['role']);
            header("Location: index.php");
            exit();
        } else {
            write_log('LOGIN', $email, 'FAILURE', 'Invalid credentials');
            $erreur = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

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
    <title>ParisSport+ - Accueil</title>

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
<main class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 500px; width: 100%;">
        <h1 class="text-center mb-3">ParisSport+</h1>
        <h3 class="text-center mb-4">Connexion</h3>

        <?php if (!empty($erreur))
            echo "<div class='alert alert-danger'>$erreur</div>"; ?>

        <!-- Démo : affichage et contrôle du Session ID -->
        <div class="mb-3 p-3" style="background:#f8f9fa;border-radius:8px;">
            <strong>Session ID actuel : </strong>
            <span id="sid">chargement...</span>
            <div class="mt-2">
                <button id="sid-refresh" type="button" class="btn btn-sm btn-secondary">🔄 Rafraîchir</button>
                <button id="sid-login" type="button" class="btn btn-sm btn-primary">🔐 Se connecter (demo)</button>
            </div>
        </div>

        <form id="login-form" method="post">
            <?php csrf_input(); ?>
            <div class="form-floating mb-3">
                <input type="email" class="form-control2 w-100 rounded-pill" id="email" name="email" placeholder="Email"
                    required>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control2 w-100 rounded-pill" id="password" name="password"
                    placeholder="Mot de passe" required>
            </div>

            <button type='submit' class="btn btn-secondary w-100 rounded-pill"
                style="background-color: #2B9348; border-color: #2B9348;">
                Se connecter
            </button>


            <p class="text-center mt-3 mb-0">Pas encore de compte ?
                <a href="register.php" class="text-decoration-none text-primary fw-bold">S'inscrire</a>
            </p>
        </form>
    </div>
</main>

<?php include('includes/footer.php'); ?>
<script src="js/script.js" nonce="<?= $nonce ?>"></script>

<script>
    async function loadSID() {
        try {
            const res = await fetch('session-info.php');
            const data = await res.json();
            document.getElementById('sid').textContent = data.sessionId || '(aucune session)';
        } catch (e) {
            document.getElementById('sid').textContent = '(erreur)';
            console.error(e);
        }
    }

    document.getElementById('sid-refresh').addEventListener('click', loadSID);

    document.getElementById('sid-login').addEventListener('click', async () => {
        // Envoie les mêmes champs que le formulaire de login en AJAX
        const form = document.getElementById('login-form');
        const formData = new FormData(form);

        try {
            await fetch('login.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            // Après le POST, recharge l'ID de session ; si login réussi le serveur aura régénéré l'ID
            await loadSID();

            // Affiche un message court
            const sid = document.getElementById('sid').textContent;
            if (sid && sid !== '(aucune session)') {
                alert('Session créée / renouvelée — Session ID: ' + sid);
            } else {
                alert('Connexion échouée ou aucune session créée. Vérifiez vos identifiants.');
            }
        } catch (e) {
            console.error(e);
            alert('Erreur lors de la requête de connexion.');
        }
    });

    // Charge initial
    loadSID();
</script>