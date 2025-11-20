<?php
require_once("config/database2.php");
session_start();

include('includes/header.php');

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 1) {
    http_response_code(403);
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>403 — Accès refusé</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet' nonce='<?= $nonce ?>'>
    </head>
    <body>
    <div class='container mt-5'>
        <h1 class='display-6'>403 — Accès refusé</h1>
        <p>Vous n'avez pas la permission d'accéder à cette page.</p>
        <a href='index.php' class='btn btn-secondary'>Retour</a>
    </div>
    </body>
    </html>";
    exit();
}
// Récupérer le terme de recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$keywords = explode(' ', $search);

$sql = "SELECT * FROM equipements_sportifs_paris";

// On crée un tableau pour les paramètres et les conditions
$whereClauses = [];
$params = [];

// On applique chaque mot-clé à chaque colonne (nom, adresse, type_sport, arrondissement)
foreach ($keywords as $keyword) {
    if (!empty($keyword)) {
        $whereClauses[] = "(nom LIKE :keyword OR adresse LIKE :keyword OR type_sport LIKE :keyword OR arrondissement LIKE :keyword)";
        $params['keyword'] = '%' . $keyword . '%';  // On recherche ce mot-clé
    }
}

// Si des conditions ont été créées (au moins un mot-clé), on les ajoute à la requête
if (count($whereClauses) > 0) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$equipements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - ParisSport+</title>

    <!-- Inclusion de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        nonce="<?= $nonce ?>">

    <!-- Vos styles personnalisés -->
    <link rel="stylesheet" href="css/style.css" nonce="<?= $nonce ?>">

    <!-- Inclusion des polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"
        nonce="<?= $nonce ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/P+-removebg.png" sizes="16x16">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Gestion des Équipements Sportifs</h1>

        <!-- Barre de recherche -->
        <div class="row mb-3">
            <div class="col-md-6 offset-md-3">
                <form method="get" class="input-group mb-3 flex-nowrap flex-md-wrap">
                    <input type="text" name="search" class="form-control"
                        placeholder="Rechercher par nom, adresse, sport ou code postal"
                        value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary" style="background-color: #2B9348; border-color: #2B9348;"
                        type="submit">Rechercher</button>
                </form>
            </div>
        </div>

        <!-- Bouton pour ajouter un équipement -->
        <div class="text-end mb-3">
            <a href="add_equipement.php" class="btn btn-primary"
                style="background-color: #2B9348; border-color: #2B9348;">Ajouter un équipement</a>
        </div>

    <!-- Tableau des équipements -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Sport</th>
                    <th>Code Postal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($equipements) > 0): ?>
                    <?php foreach($equipements as $eq): ?>
                    <tr>
                        <td><?= htmlspecialchars($eq['id']) ?></td>
                        <td><?= htmlspecialchars($eq['nom']) ?></td>
                        <td><?= htmlspecialchars($eq['adresse']) ?></td>
                        <td><?= htmlspecialchars($eq['type_sport']) ?></td>
                        <td><?= htmlspecialchars($eq['arrondissement']) ?></td>
                        <td>
<?php require_once('includes/csrf.php'); ?>
                            <a href="edit_equipement.php?id=<?= urlencode($eq['id']) ?>" class="btn btn-warning btn-sm me-2">Modifier</a>
                            <a href="delete_equipement.php?id=<?= urlencode($eq['id']) ?>&token=<?= generate_csrf_token() ?>" onclick="return confirm('Supprimer cet équipement ?')" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aucun équipement trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    <!-- Inclusion de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        nonce="<?= $nonce ?>"></script>
    <?php include('includes/footer.php'); ?>
</body>

</html>