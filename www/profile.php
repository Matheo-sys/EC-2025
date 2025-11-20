<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    echo "Utilisateur non connecté, redirection vers login.php";
    header('Location: login.php');
    exit();
}


include('config/database.php');

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

// Traitement du formulaire de modification de profil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $newEmail = $_POST['email'] ?? '';
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $avatar = $_FILES['avatar'] ?? null;

    $errors = [];


    if (!password_verify($currentPassword, $user['mot_de_passe'])) {
        $errors[] = "Le mot de passe actuel est incorrect.";
    }

    // Vérification de l'avatar
    if ($avatar && $avatar['error'] == 0) {

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = pathinfo($avatar['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            $errors[] = "Le format d'image n'est pas autorisé. Les formats acceptés sont : jpg, jpeg, png, gif.";
        } else {

            $avatarPath = 'uploads/avatars/' . basename($avatar['name']);
            if (move_uploaded_file($avatar['tmp_name'], $avatarPath)) {

                $stmt = $conn->prepare("UPDATE utilisateurs SET avatar = ? WHERE id = ?");
                $stmt->execute([$avatarPath, $userId]);
                $_SESSION['user']['avatar'] = $avatarPath; // Mise à jour dans la session
            } else {
                $errors[] = "Erreur lors de l'upload de l'avatar.";
            }
        }
    }

    // Vérification de l'email
    if ($newEmail && $newEmail != $user['email']) {

        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$newEmail]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $errors[] = "L'email est déjà utilisé par un autre utilisateur.";
        }

        // Si aucune erreur, mettre à jour l'email
        if (empty($errors)) {
            $stmt = $conn->prepare("UPDATE utilisateurs SET email = ? WHERE id = ?");
            $stmt->execute([$newEmail, $userId]);
            $_SESSION['user']['email'] = $newEmail; // Mise à jour dans la session
        }
    }

    // Vérification du changement de mot de passe
    if ($newPassword || $confirmPassword) {

        if ($newPassword !== $confirmPassword) {
            $errors[] = "Les nouveaux mots de passe ne correspondent pas.";
        } else {

            if (!password_verify($currentPassword, $user['mot_de_passe'])) {
                $errors[] = "Le mot de passe actuel est incorrect.";
            }

            // Si les mots de passe correspondent, mettre à jour le mot de passe
            if (empty($errors)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $userId]);
            }
        }
    }

    // Si aucune erreur, rediriger ou afficher un message
    if (empty($errors)) {
        echo "Profil mis à jour avec succès!";
        header('Location: profile.php');
        exit();
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

<body>

    <?php include('includes/header.php'); ?>

    <main class="container mt-5">
        <h1 class="text-center mb-4">Mon Profil</h1>

        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <!-- Affichage des informations de l'utilisateur -->
                <div class="card">
                    <div class="card-body text-center">

                        <img src="<?= htmlspecialchars($user['avatar'] ?? 'assets/default-avatar.png') ?>" alt="Avatar"
                            class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                        <h3 class="mt-3"><?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?>
                        </h3>
                        <p class="mt-2"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de mise à jour -->
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-6">
                <h3>Modifier mes informations</h3>
                <form method="POST" enctype="multipart/form-data">
                    <!-- Changer l'avatar -->
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Changer l'avatar</label>
                        <input type="file" class="form-control" id="avatar" name="avatar">
                    </div>

                    <!-- Email (optionnel, uniquement si l'utilisateur veut le changer) -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($user['email']) ?>">
                    </div>

                    <!-- Mot de passe actuel (nécessaire pour toute modification de l'email ou du mot de passe) -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control" id="current_password" name="current_password"
                            required>
                    </div>

                    <!-- Nouveau mot de passe (optionnel, uniquement si l'utilisateur veut le changer) -->
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>

                    <!-- Confirmation du mot de passe (optionnel) -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>

                    <button type="submit" class="btn btn-secondary w-100 rounded-pill"
                        style="background-color: #2B9348; border-color: #2B9348;">Mettre à jour</button>
                </form>

                <?php
                // Afficher les erreurs 
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger mt-3'>$error</div>";
                    }
                }
                ?>
            </div>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>

</body>

</html>