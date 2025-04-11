<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

include('config/database.php');

$userId = $_SESSION['user']['id'];

// suppression d'un like 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['element_id'])) {
    $elementId = $_POST['element_id'];
    
    $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND element_id = ?");
    $stmt->execute([$userId, $elementId]);
    
    header("Location: mylikes.php");
    exit();
}

// Récupération des terrains likés 
$stmt = $conn->prepare("
    SELECT e.*
    FROM equipements_sportifs_paris e
    INNER JOIN likes l ON e.id = l.element_id
    WHERE l.user_id = ?
    ORDER BY l.created_at desc
");
$stmt->execute([$userId]);
$likedTerrains = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Likes - ParisSport+</title>
    
    <!-- Inclusion de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Vos styles personnalisés -->
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Inclusion des polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
</head>
<body>
    
<?php include('includes/header.php'); ?>

<main class="container mt-5">
    <h1 class="text-center mb-4">Mes Likes</h1>
    <div class="text-center mb-4">
        <a href="index.php" class="btn btn-secondary w-20" style="background-color: #2B9348; border-color: #2B9348;">Retour à l'accueil</a>
    </div>
    <?php if (empty($likedTerrains)): ?>
        <p class="text-center">Vous n'avez encore rien liké.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($likedTerrains as $terrain): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($terrain['nom']) ?></h5>
                            <p class="card-text">Adresse : <?= htmlspecialchars($terrain['adresse']) ?></p>
                            <p class="card-text">Sport : <?= htmlspecialchars($terrain['type_sport']) ?></p>
                            
                            <form method="POST" action="mylikes.php">
                                <input type="hidden" name="element_id" value="<?= htmlspecialchars($terrain['id']) ?>">
                                <button type="submit" class="btn btn-danger w-100">Supprimer le like</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include('includes/footer.php'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
